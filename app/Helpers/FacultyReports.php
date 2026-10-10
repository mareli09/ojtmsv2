<?php

namespace App\Helpers;

use App\Models\IncidentReport;
use App\Models\Section;
use App\Models\StudentChecklist;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * Builds the faculty reports from live data.
 *
 * Every report is scoped to the sections the faculty member handles, and is
 * returned in one shape - title, headers, rows, summary - so the same dataset
 * drives the on-screen preview, the CSV download and the printable view.
 */
class FacultyReports
{
    /** The full requirement checklist, in submission order. */
    public const REQUIREMENTS = [
        'Medical Record', 'Receipt of OJT Kit', 'Waiver', 'Endorsement letter', 'MOA',
        'Registration card', 'DTR', 'Weekly report', 'Monthly appraisal',
        'Supervisor evaluation', 'Certificate of completion',
    ];

    /** Hours a student must log before the DTR requirement is met. */
    public const DEFAULT_TARGET_HOURS = 486;

    /**
     * The reports offered on the page, in display order.
     */
    public static function catalogue(): array
    {
        return [
            'student-progress' => [
                'title' => 'Student Progress Report',
                'description' => 'Completion of the 11 OJT requirements per student, with pending and declined counts.',
                'icon' => 'fa-user-graduate',
            ],
            'dtr-hours' => [
                'title' => 'DTR & Training Hours',
                'description' => 'Approved hours logged against the required target, per student.',
                'icon' => 'fa-clock',
            ],
            'requirement-compliance' => [
                'title' => 'Requirement Compliance',
                'description' => 'How each requirement is tracking across the whole section.',
                'icon' => 'fa-clipboard-check',
            ],
            'pending-review' => [
                'title' => 'Pending Review Queue',
                'description' => 'Submissions still waiting on you, oldest first.',
                'icon' => 'fa-hourglass-half',
            ],
            'incident-log' => [
                'title' => 'Incident Report Log',
                'description' => 'Incidents filed by your students and how they were handled.',
                'icon' => 'fa-triangle-exclamation',
            ],
        ];
    }

    public static function exists(string $key): bool
    {
        return array_key_exists($key, self::catalogue());
    }

    /**
     * Build one report for a faculty member.
     *
     * @return array{title:string,subtitle:string,headers:array,rows:array,summary:array,empty:string}
     */
    public static function build(string $key, User $faculty): array
    {
        $context = self::context($faculty);

        $report = match ($key) {
            'student-progress'       => self::studentProgress($context),
            'dtr-hours'              => self::dtrHours($context),
            'requirement-compliance' => self::requirementCompliance($context),
            'pending-review'         => self::pendingReview($context),
            'incident-log'           => self::incidentLog($context),
        };

        $sectionNames = $context['sections']->pluck('name')->implode(', ') ?: 'No section assigned';

        return $report + [
            'key'      => $key,
            'subtitle' => $sectionNames . ' · ' . $context['students']->count() . ' students · generated ' . now()->format('M d, Y g:i A'),
            'summary'  => [],
            'empty'    => 'No data available yet.',
        ];
    }

    /**
     * Shared lookups every report needs.
     */
    private static function context(User $faculty): array
    {
        $sections = Section::where('faculty_id', $faculty->id)->whereNull('deleted_at')->orderBy('name')->get();

        $students = User::where('role', 'student')
            ->whereNull('deleted_at')
            ->whereIn('section_id', $sections->pluck('id'))
            ->orderBy('last_name')
            ->get();

        $entries = StudentChecklist::whereNull('deleted_at')
            ->whereIn('student_id', $students->pluck('id'))
            ->get();

        return [
            'faculty'  => $faculty,
            'sections' => $sections,
            'students' => $students,
            'entries'  => $entries,
        ];
    }

    private static function sectionName(array $context, $sectionId): string
    {
        return $context['sections']->firstWhere('id', $sectionId)->name ?? '—';
    }

    // ────────────────────────────────────────────────────────────
    // 1. Student progress
    // ────────────────────────────────────────────────────────────
    private static function studentProgress(array $context): array
    {
        $total = count(self::REQUIREMENTS);
        $rows  = [];
        $completed = 0;

        foreach ($context['students'] as $student) {
            $entries  = $context['entries']->where('student_id', $student->id);
            $approved = 0;

            foreach (self::REQUIREMENTS as $item) {
                if ($entries->where('item', $item)->where('faculty_status', 'approved')->isNotEmpty()) {
                    $approved++;
                }
            }

            $pct = $total > 0 ? (int) round($approved / $total * 100) : 0;
            if ($approved >= $total) {
                $completed++;
            }

            $rows[] = [
                $student->student_id ?? '—',
                trim($student->first_name . ' ' . $student->last_name),
                self::sectionName($context, $student->section_id),
                $approved,
                $total,
                $pct . '%',
                $entries->where('faculty_status', 'pending')->count(),
                $entries->where('faculty_status', 'declined')->count(),
                (int) $entries->where('item', 'DTR')->where('faculty_status', 'approved')->sum('student_dtr_hours'),
                self::progressLabel($approved, $total, $entries->count()),
            ];
        }

        // Least complete first - the students who need attention lead the report.
        usort($rows, fn ($a, $b) => $a[3] <=> $b[3]);

        return [
            'title'   => 'Student Progress Report',
            'headers' => ['Student ID', 'Student Name', 'Section', 'Approved', 'Required',
                          'Completion', 'Pending', 'Declined', 'DTR Hours', 'Standing'],
            'rows'    => $rows,
            'summary' => [
                'Students'        => count($rows),
                'Fully complete'  => $completed,
                'Still in progress' => count($rows) - $completed,
            ],
            'empty'   => 'No students are enrolled in your section yet.',
        ];
    }

    private static function progressLabel(int $approved, int $total, int $submissions): string
    {
        if ($approved >= $total)  return 'Completed';
        if ($submissions === 0)   return 'Not started';
        if ($approved >= $total * 0.6) return 'On track';

        return 'Needs follow-up';
    }

    // ────────────────────────────────────────────────────────────
    // 2. DTR and training hours
    // ────────────────────────────────────────────────────────────
    private static function dtrHours(array $context): array
    {
        $rows = [];
        $totalHours = 0;
        $metTarget  = 0;

        foreach ($context['students'] as $student) {
            $dtr = $context['entries']->where('student_id', $student->id)->where('item', 'DTR');

            $approvedEntries = $dtr->where('faculty_status', 'approved');
            $hours  = (int) $approvedEntries->sum('student_dtr_hours');
            $target = (int) ($dtr->max('faculty_dtr_target_hours') ?: self::DEFAULT_TARGET_HOURS);
            $latest = $dtr->sortByDesc('student_submitted_at')->first();

            $totalHours += $hours;
            if ($hours >= $target) {
                $metTarget++;
            }

            $rows[] = [
                $student->student_id ?? '—',
                trim($student->first_name . ' ' . $student->last_name),
                self::sectionName($context, $student->section_id),
                $dtr->count(),
                $approvedEntries->count(),
                $dtr->where('faculty_status', 'pending')->count(),
                $hours,
                $target,
                max(0, $target - $hours),
                ($target > 0 ? (int) round($hours / $target * 100) : 0) . '%',
                $latest->student_dtr_week ?? '—',
                $latest && $latest->student_submitted_at
                    ? Carbon::parse($latest->student_submitted_at)->format('M d, Y')
                    : 'No submission',
            ];
        }

        usort($rows, fn ($a, $b) => $a[6] <=> $b[6]);

        return [
            'title'   => 'DTR & Training Hours',
            'headers' => ['Student ID', 'Student Name', 'Section', 'Entries', 'Approved', 'Pending',
                          'Approved Hours', 'Target', 'Remaining', 'Progress', 'Latest Week', 'Last Submitted'],
            'rows'    => $rows,
            'summary' => [
                'Students'          => count($rows),
                'Total hours logged' => $totalHours,
                'Target reached'    => $metTarget,
            ],
            'empty'   => 'No DTR entries have been submitted yet.',
        ];
    }

    // ────────────────────────────────────────────────────────────
    // 3. Requirement compliance
    // ────────────────────────────────────────────────────────────
    private static function requirementCompliance(array $context): array
    {
        $studentCount = $context['students']->count();
        $rows = [];

        foreach (self::REQUIREMENTS as $item) {
            $entries = $context['entries']->where('item', $item);
            $submittedBy = $entries->pluck('student_id')->unique()->count();
            $approvedBy  = $entries->where('faculty_status', 'approved')->pluck('student_id')->unique()->count();

            $rows[] = [
                $item,
                $entries->count(),
                $entries->where('faculty_status', 'approved')->count(),
                $entries->where('faculty_status', 'pending')->count(),
                $entries->where('faculty_status', 'declined')->count(),
                $submittedBy,
                max(0, $studentCount - $submittedBy),
                ($studentCount > 0 ? (int) round($approvedBy / $studentCount * 100) : 0) . '%',
            ];
        }

        return [
            'title'   => 'Requirement Compliance',
            'headers' => ['Requirement', 'Submissions', 'Approved', 'Pending', 'Declined',
                          'Students Submitted', 'Students Missing', 'Cleared'],
            'rows'    => $rows,
            'summary' => [
                'Requirements tracked' => count(self::REQUIREMENTS),
                'Total submissions'    => $context['entries']->count(),
                'Awaiting review'      => $context['entries']->where('faculty_status', 'pending')->count(),
            ],
            'empty'   => 'No submissions have been made yet.',
        ];
    }

    // ────────────────────────────────────────────────────────────
    // 4. Pending review queue
    // ────────────────────────────────────────────────────────────
    private static function pendingReview(array $context): array
    {
        $pending = $context['entries']->where('faculty_status', 'pending')
            ->sortBy('student_submitted_at');

        $rows = [];
        $overdue = 0;

        foreach ($pending as $entry) {
            $student   = $context['students']->firstWhere('id', $entry->student_id);
            $submitted = $entry->student_submitted_at ? Carbon::parse($entry->student_submitted_at) : null;
            $waiting   = $submitted ? (int) $submitted->diffInDays(now()) : 0;

            if ($waiting > 7) {
                $overdue++;
            }

            $rows[] = [
                $submitted ? $submitted->format('M d, Y') : '—',
                $waiting,
                $student->student_id ?? '—',
                $student ? trim($student->first_name . ' ' . $student->last_name) : 'Unknown',
                self::sectionName($context, $entry->section_id),
                $entry->item,
                self::entryDetail($entry),
            ];
        }

        return [
            'title'   => 'Pending Review Queue',
            'headers' => ['Submitted', 'Days Waiting', 'Student ID', 'Student Name', 'Section', 'Requirement', 'Details'],
            'rows'    => $rows,
            'summary' => [
                'Awaiting review'      => count($rows),
                'Waiting over 7 days'  => $overdue,
            ],
            'empty'   => 'Nothing is waiting for review — you are all caught up.',
        ];
    }

    /**
     * A short, human-readable detail line for whichever requirement this is.
     */
    private static function entryDetail(StudentChecklist $entry): string
    {
        return match ($entry->item) {
            'DTR'                   => trim(($entry->student_dtr_week ?? '') . ' — ' . ($entry->student_dtr_hours ?? 0) . ' hrs', ' —'),
            'Weekly report'         => $entry->student_weekly_week ?? '—',
            'Monthly appraisal'     => trim(($entry->student_appraisal_month ?? '') . ' — rating ' . ($entry->student_appraisal_grade_rating ?? 'n/a'), ' —'),
            'Supervisor evaluation' => 'Rating ' . ($entry->student_supervisor_eval_grade ?? 'n/a'),
            'Certificate of completion' => $entry->student_coc_company ?? '—',
            'Medical Record'        => $entry->student_clinic_name ?? '—',
            'Receipt of OJT Kit'    => $entry->student_receipt_number ?? '—',
            'Waiver'                => $entry->student_guardian_name ?? '—',
            default                 => $entry->student_remarks ?? '—',
        };
    }

    // ────────────────────────────────────────────────────────────
    // 5. Incident log
    // ────────────────────────────────────────────────────────────
    private static function incidentLog(array $context): array
    {
        $incidents = IncidentReport::whereIn('section_id', $context['sections']->pluck('id'))
            ->orderByDesc('created_at')
            ->get();

        $labels = [
            'pending'      => 'Pending',
            'reviewing'    => 'Under review',
            'taken_action' => 'Action taken',
            'resolved'     => 'Resolved',
            'declined'     => 'Declined',
        ];

        $rows = [];

        foreach ($incidents as $incident) {
            $student = $context['students']->firstWhere('id', $incident->student_id)
                ?? User::find($incident->student_id);

            $rows[] = [
                $incident->created_at ? $incident->created_at->format('M d, Y') : '—',
                $incident->incident_date ? Carbon::parse($incident->incident_date)->format('M d, Y') : '—',
                $student ? trim($student->first_name . ' ' . $student->last_name) : 'Unknown',
                self::sectionName($context, $incident->section_id),
                $incident->type,
                $incident->location ?? '—',
                $labels[$incident->faculty_status] ?? ucfirst((string) $incident->faculty_status),
                $incident->faculty_reviewed_at ? Carbon::parse($incident->faculty_reviewed_at)->format('M d, Y') : 'Not yet',
                $incident->faculty_remarks ?: '—',
            ];
        }

        return [
            'title'   => 'Incident Report Log',
            'headers' => ['Date Filed', 'Incident Date', 'Student', 'Section', 'Type',
                          'Location', 'Status', 'Reviewed On', 'Faculty Remarks'],
            'rows'    => $rows,
            'summary' => [
                'Total incidents' => $incidents->count(),
                'Still open'      => $incidents->whereIn('faculty_status', ['pending', 'reviewing'])->count(),
                'Action taken'    => $incidents->where('faculty_status', 'taken_action')->count(),
                'Closed'          => $incidents->whereIn('faculty_status', ['resolved', 'declined'])->count(),
            ],
            'empty'   => 'No incident reports have been filed in your section.',
        ];
    }

    /**
     * Filename for a downloaded copy of a report.
     */
    public static function filename(string $key, string $extension): string
    {
        return $key . '_' . now()->format('Y-m-d_H-i-s') . '.' . $extension;
    }
}
