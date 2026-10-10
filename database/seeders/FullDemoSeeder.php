<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\AnnouncementRead;
use App\Models\IncidentReport;
use App\Models\Section;
use App\Models\StudentChecklist;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * FULL DEMO SEEDER — wipes every transactional table and rebuilds a complete,
 * realistic dataset covering every state the UI can display.
 *
 *   php artisan db:seed --class=FullDemoSeeder
 *   php artisan migrate:fresh --seed        (DatabaseSeeder calls this)
 *
 * Every account uses the password: password
 */
class FullDemoSeeder extends Seeder
{
    /** Requirements tracked by the checklist, in submission order. */
    private const REQUIREMENTS = [
        'Medical Record', 'Receipt of OJT Kit', 'Waiver', 'Endorsement letter', 'MOA',
        'Registration card', 'DTR', 'Weekly report', 'Monthly appraisal',
        'Supervisor evaluation', 'Certificate of completion',
    ];

    private const COMPANIES = [
        ['name' => 'ABC Tech Solutions Inc.',   'supervisor' => 'Mr. Jonathan Cruz',   'address' => 'Makati City'],
        ['name' => 'Northwind Systems Corp.',   'supervisor' => 'Ms. Ana Torres',      'address' => 'Ortigas Center, Pasig'],
        ['name' => 'BrightPath Software',       'supervisor' => 'Engr. Paolo Ramirez', 'address' => 'Quezon City'],
        ['name' => 'Cebu Data Services',        'supervisor' => 'Ms. Grace Lim',       'address' => 'Cebu Business Park'],
        ['name' => 'Metro Digital Innovations', 'supervisor' => 'Mr. Enrico Bautista', 'address' => 'Taguig City'],
    ];

    public function run(): void
    {
        $this->wipe();

        $this->call(CMSSeeder::class);

        $sections = $this->seedSections();
        $admins   = $this->seedAdmins();
        $faculty  = $this->seedFaculty($sections);
        $students = $this->seedStudents($sections);

        $this->seedChecklists($students);
        $this->seedIncidentReports($students);

        $announcements = $this->seedAnnouncements($admins['admin'], $faculty['mreyes']);
        $this->seedAnnouncementReads($announcements, $students);

        $this->call(FaqSeeder::class);

        $this->summary();
    }

    // ────────────────────────────────────────────────────────────
    // 1. WIPE
    // ────────────────────────────────────────────────────────────
    private function wipe(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach (['chat_messages', 'announcement_reads', 'student_checklists', 'incident_reports',
                  'announcements', 'faqs', 'users', 'sections', 'password_reset_tokens'] as $table) {
            DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->warn('All existing users, sections and submissions were removed.');
    }

    // ────────────────────────────────────────────────────────────
    // 2. SECTIONS
    // ────────────────────────────────────────────────────────────
    private function seedSections(): array
    {
        $defaults = [
            'school_year' => '2025-2026',
            'term'        => 'Term 2',
            'status'      => 'active',
            'faculty_id'  => null,
        ];

        $sections = [];

        $sections['4A'] = Section::create($defaults + [
            'name' => 'BSIT-4A', 'day' => 'Monday, Wednesday',
            'start_time' => '08:00', 'end_time' => '12:00', 'days_count' => 2,
            'room' => 'Room 301', 'capacity' => 35,
            'description' => 'BS Information Technology — 4th Year, Section A',
        ]);

        $sections['4B'] = Section::create($defaults + [
            'name' => 'BSIT-4B', 'day' => 'Tuesday, Thursday',
            'start_time' => '13:00', 'end_time' => '17:00', 'days_count' => 2,
            'room' => 'Room 302', 'capacity' => 35,
            'description' => 'BS Information Technology — 4th Year, Section B',
        ]);

        $sections['4C'] = Section::create($defaults + [
            'name' => 'BSIT-4C', 'day' => 'Monday, Wednesday, Friday',
            'start_time' => '08:00', 'end_time' => '11:00', 'days_count' => 3,
            'room' => 'Lab 2', 'capacity' => 30,
            'description' => 'BS Information Technology — 4th Year, Section C',
        ]);

        // Deliberately left without a faculty member so the admin dashboard has
        // something real to flag under "Needs Your Attention".
        $sections['CS4A'] = Section::create($defaults + [
            'name' => 'BSCS-4A', 'day' => 'Saturday',
            'start_time' => '09:00', 'end_time' => '15:00', 'days_count' => 1,
            'room' => 'Room 405', 'capacity' => 40,
            'description' => 'BS Computer Science — 4th Year, Section A (awaiting faculty assignment)',
        ]);

        $this->command->info('Sections: BSIT-4A, BSIT-4B, BSIT-4C, BSCS-4A (unassigned)');

        return $sections;
    }

    // ────────────────────────────────────────────────────────────
    // 3. USERS
    // ────────────────────────────────────────────────────────────
    private function seedAdmins(): array
    {
        $admin = $this->makeUser([
            'employee_id' => 'ADM-001', 'role' => 'admin',
            'first_name' => 'System', 'last_name' => 'Administrator',
            'username' => 'admin', 'email' => 'admin@ojtms.edu.ph',
            'contact' => '09170000001', 'department' => 'OJT Program Office',
        ], monthsAgo: 6, activeDaysAgo: 0);

        $coordinator = $this->makeUser([
            'employee_id' => 'ADM-002', 'role' => 'admin',
            'first_name' => 'Teresa', 'middle_name' => 'B.', 'last_name' => 'Navarro',
            'username' => 'tnavarro', 'email' => 'tnavarro@ojtms.edu.ph',
            'contact' => '09170000002', 'department' => 'OJT Program Office',
        ], monthsAgo: 5, activeDaysAgo: 2);

        $this->command->info('Admins: admin, tnavarro');

        return ['admin' => $admin, 'tnavarro' => $coordinator];
    }

    private function seedFaculty(array $sections): array
    {
        $faculty = [];

        $faculty['mreyes'] = $this->makeUser([
            'employee_id' => 'FAC-101', 'role' => 'faculty',
            'first_name' => 'Maria', 'middle_name' => 'L.', 'last_name' => 'Reyes',
            'username' => 'mreyes', 'email' => 'mreyes@ojtms.edu.ph',
            'contact' => '09171111101', 'department' => 'College of Computer Studies',
            'section_id' => $sections['4A']->id,
        ], monthsAgo: 6, activeDaysAgo: 1);

        $faculty['jsantos'] = $this->makeUser([
            'employee_id' => 'FAC-102', 'role' => 'faculty',
            'first_name' => 'Jose', 'middle_name' => 'P.', 'last_name' => 'Santos',
            'username' => 'jsantos', 'email' => 'jsantos@ojtms.edu.ph',
            'contact' => '09171111102', 'department' => 'College of Computer Studies',
            'section_id' => $sections['4B']->id,
        ], monthsAgo: 6, activeDaysAgo: 3);

        $faculty['lgomez'] = $this->makeUser([
            'employee_id' => 'FAC-103', 'role' => 'faculty',
            'first_name' => 'Liza', 'last_name' => 'Gomez',
            'username' => 'lgomez', 'email' => 'lgomez@ojtms.edu.ph',
            'contact' => '09171111103', 'department' => 'College of Computer Studies',
            'section_id' => $sections['4C']->id,
        ], monthsAgo: 4, activeDaysAgo: 6);

        // Not handling any section yet — another real dashboard insight.
        $faculty['rdizon'] = $this->makeUser([
            'employee_id' => 'FAC-104', 'role' => 'faculty',
            'first_name' => 'Ramon', 'last_name' => 'Dizon',
            'username' => 'rdizon', 'email' => 'rdizon@ojtms.edu.ph',
            'contact' => '09171111104', 'department' => 'College of Computer Studies',
        ], monthsAgo: 2, activeDaysAgo: 20);

        $sections['4A']->update(['faculty_id' => $faculty['mreyes']->id]);
        $sections['4B']->update(['faculty_id' => $faculty['jsantos']->id]);
        $sections['4C']->update(['faculty_id' => $faculty['lgomez']->id]);

        $this->command->info('Faculty: mreyes (4A), jsantos (4B), lgomez (4C), rdizon (no section)');

        return $faculty;
    }

    /**
     * 20 students spread across the sections, each tagged with how far along
     * their OJT requirements are.
     */
    private function seedStudents(array $sections): array
    {
        $roster = [
            // [first, last, section key, progress profile, months ago, last active (days)]
            ['Anna',     'Cruz',        '4A',   'complete',  6, 1],
            ['Miguel',   'Dela Torre',  '4A',   'advanced',  6, 2],
            ['Sofia',    'Villanueva',  '4A',   'advanced',  6, 1],
            ['Carlo',    'Mendoza',     '4A',   'midway',    5, 4],
            ['Bea',      'Fernandez',   '4A',   'midway',    5, 9],
            ['Rafael',   'Ocampo',      '4A',   'starting',  4, 3],
            ['Katrina',  'Bautista',    '4B',   'complete',  6, 5],
            ['Diego',    'Salazar',     '4B',   'advanced',  6, 2],
            ['Patricia', 'Lim',         '4B',   'midway',    5, 8],
            ['Joshua',   'Ramos',       '4B',   'midway',    4, 1],
            ['Angelica', 'Torres',      '4B',   'starting',  4, 12],
            ['Mark',     'Aquino',      '4B',   'none',      3, 30],
            ['Isabel',   'Navarro',     '4C',   'advanced',  5, 2],
            ['Lorenzo',  'Garcia',      '4C',   'midway',    4, 6],
            ['Camille',  'Domingo',     '4C',   'midway',    3, 3],
            ['Enrique',  'Pascual',     '4C',   'starting',  3, 15],
            ['Nicole',   'Reyes',       '4C',   'starting',  2, 4],
            ['Gabriel',  'Santiago',    '4C',   'none',      2, 45],
            // Not yet enrolled in a section
            ['Trisha',   'Manalo',      null,   'none',      1, 2],
            ['Kevin',    'Alonzo',      null,   'none',      1, 7],
        ];

        $students = [];

        foreach ($roster as $index => [$first, $last, $sectionKey, $profile, $monthsAgo, $activeDaysAgo]) {
            $company  = self::COMPANIES[$index % count(self::COMPANIES)];
            $username = strtolower(substr($first, 0, 1) . str_replace([' ', "'"], '', $last));

            // Two accounts are deactivated so the admin views have that case too.
            $status = in_array($username, ['maquino', 'gsantiago'], true) ? 'inactive' : 'active';

            $user = $this->makeUser([
                'student_id'  => sprintf('2026-%04d', 1001 + $index),
                'role'        => 'student',
                'first_name'  => $first,
                'last_name'   => $last,
                'username'    => $username,
                'email'       => $username . '@student.ojtms.edu.ph',
                'contact'     => '0918' . str_pad((string) (2220000 + $index), 7, '0', STR_PAD_LEFT),
                'department'  => $sectionKey === 'CS4A' ? 'BS Computer Science' : 'BS Information Technology',
                'section_id'      => $sectionKey ? $sections[$sectionKey]->id : null,
                'company_name'    => $company['name'],
                'company_address' => $company['address'],
                'status'      => $status,
            ], monthsAgo: $monthsAgo, activeDaysAgo: $activeDaysAgo);

            $students[] = [
                'user'    => $user,
                'section' => $sectionKey ? $sections[$sectionKey] : null,
                'profile' => $profile,
                'company' => $company,
            ];
        }

        $this->command->info('Students: 20 (18 enrolled, 2 unassigned, 2 inactive)');

        return $students;
    }

    /**
     * Create a user, then backdate it so the dashboard's registration trend and
     * "active this week" figures have a real shape.
     */
    private function makeUser(array $attributes, int $monthsAgo, int $activeDaysAgo): User
    {
        $created = now()->subMonths($monthsAgo)->subDays(rand(0, 20))->setTime(rand(8, 17), rand(0, 59));

        $user = User::create($attributes + [
            'password' => bcrypt('password'),
            'status'   => 'active',
        ]);

        DB::table('users')->where('id', $user->id)->update([
            'name'             => trim($attributes['first_name'] . ' ' . $attributes['last_name']),
            'created_at'       => $created,
            'updated_at'       => $created,
            'last_activity_at' => now()->subDays($activeDaysAgo),
        ]);

        return $user->refresh();
    }

    // ────────────────────────────────────────────────────────────
    // 4. CHECKLISTS
    // ────────────────────────────────────────────────────────────
    private function seedChecklists(array $students): void
    {
        $rows = 0;

        foreach ($students as $student) {
            if (!$student['section'] || $student['profile'] === 'none') {
                continue;
            }

            $rows += $this->seedChecklistFor($student);
        }

        $this->command->info("Checklist submissions: {$rows} across every review state");
    }

    private function seedChecklistFor(array $student): int
    {
        // How many of the ordered requirements the student has reached, and how
        // the tail of that list was reviewed.
        $plan = match ($student['profile']) {
            'complete' => ['approved' => 11, 'pending' => 0, 'declined' => 0],
            'advanced' => ['approved' => 7,  'pending' => 2, 'declined' => 0],
            'midway'   => ['approved' => 4,  'pending' => 1, 'declined' => 1],
            default    => ['approved' => 1,  'pending' => 1, 'declined' => 0],
        };

        $user    = $student['user'];
        $section = $student['section'];
        $company = $student['company'];

        // Lay out the submissions first so they can be spaced backwards from
        // "a few days ago" - otherwise students with only a couple of entries
        // end up with everything stranded months in the past.
        $queue  = [];
        $cursor = 0;

        foreach (['approved', 'pending', 'declined'] as $status) {
            for ($i = 0; $i < $plan[$status]; $i++) {
                if ($cursor >= count(self::REQUIREMENTS)) {
                    break;
                }

                $item = self::REQUIREMENTS[$cursor++];

                // DTR and Weekly report are recurring - seed several entries.
                $repeats = in_array($item, ['DTR', 'Weekly report'], true)
                    ? ($student['profile'] === 'complete' ? 4 : 2)
                    : 1;

                for ($r = 1; $r <= $repeats; $r++) {
                    $queue[] = ['item' => $item, 'status' => $status, 'sequence' => $r];
                }
            }
        }

        $spacing = 9;
        $newest  = rand(2, 12);
        $age     = $newest + (count($queue) - 1) * $spacing;

        foreach ($queue as $entry) {
            StudentChecklist::create($this->checklistRow(
                $section->id, $user->id, $entry['item'], $entry['status'],
                max(1, $age), $entry['sequence'], $company
            ));
            $age -= $spacing;
        }

        return count($queue);
    }

    /**
     * Build one checklist row, filling the columns that actually belong to the
     * requirement being submitted.
     */
    private function checklistRow(int $sectionId, int $studentId, string $item, string $status, int $daysAgo, int $sequence, array $company): array
    {
        $submitted = now()->subDays($daysAgo);
        $reviewed  = $status === 'pending' ? null : now()->subDays(max(1, $daysAgo - 2));

        $remarks = match ($status) {
            'approved' => 'Verified and accepted.',
            'declined' => 'Scanned copy is unreadable. Please resubmit a clearer file.',
            default    => null,
        };

        $row = [
            'section_id'           => $sectionId,
            'student_id'           => $studentId,
            'item'                 => $item,
            'student_encoded_at'   => $submitted,
            'student_submitted_at' => $submitted,
            'faculty_status'       => $status,
            'faculty_remarks'      => $remarks,
            'faculty_reviewed_at'  => $reviewed,
        ];

        $extra = match ($item) {
            'Medical Record' => [
                'student_clinic_name'    => 'University Health Services',
                'student_clinic_address' => 'Main Campus Clinic',
            ],
            'Receipt of OJT Kit' => [
                'student_receipt_number' => 'OR-2526-' . str_pad((string) rand(1000, 9999), 4, '0', STR_PAD_LEFT),
                'student_paid_date'      => $submitted->copy()->subDay(),
            ],
            'Waiver' => [
                'student_guardian_name'    => 'Guardian of student #' . $studentId,
                'student_guardian_contact' => '0917' . rand(1000000, 9999999),
                'student_guardian_email'   => 'guardian' . $studentId . '@email.com',
                'student_guardian_social'  => 'N/A',
            ],
            'Endorsement letter' => [
                'student_endorsement_date' => $submitted->copy()->subDays(2),
                'student_start_date'       => $submitted->copy()->addDays(3),
                'student_remarks'          => 'Endorsed to ' . $company['name'] . ', ' . $company['address'] . '.',
            ],
            'MOA' => [
                'student_remarks' => 'MOA signed with ' . $company['name'] . '.',
            ],
            'Registration card' => [
                'student_enrolled_at' => $submitted->copy()->subDays(5),
            ],
            'DTR' => [
                'student_dtr_week'         => 'Week ' . $sequence . ' — ' . $submitted->format('F Y'),
                'student_dtr_hours'        => 40,
                'student_dtr_total_hours'  => 40 * $sequence,
                'student_dtr_validated_by' => $company['supervisor'],
                'faculty_dtr_target_hours' => 486,
                'faculty_dtr_reviewed_at'  => $reviewed,
            ],
            'Weekly report' => [
                'student_weekly_week'                => 'Week ' . $sequence . ' — ' . $submitted->format('F Y'),
                'student_weekly_task_description'    => 'Assisted the development team with module testing, bug fixes and documentation for the week.',
                'student_weekly_supervisor_feedback' => 'Consistent output and good communication.',
                'student_weekly_submitted_at'        => $submitted,
                'faculty_weekly_remarks'             => $remarks,
                'faculty_weekly_reviewed_at'         => $reviewed,
            ],
            'Monthly appraisal' => [
                'student_appraisal_month'        => $submitted->format('F Y'),
                'student_appraisal_grade_rating' => (string) rand(84, 96),
                'student_appraisal_evaluated_by' => $company['supervisor'],
                'student_appraisal_feedback'     => 'Meets expectations for the period.',
                'student_appraisal_submitted_at' => $submitted,
                'faculty_appraisal_remarks'      => $remarks,
                'faculty_appraisal_reviewed_at'  => $reviewed,
            ],
            'Supervisor evaluation' => [
                'student_supervisor_eval_grade'        => (string) rand(85, 98),
                'student_supervisor_eval_submitted_at' => $submitted,
                'faculty_supervisor_eval_remarks'      => $remarks,
                'faculty_supervisor_eval_reviewed_at'  => $reviewed,
            ],
            'Certificate of completion' => [
                'student_coc_company'      => $company['name'],
                'student_coc_signed_by'    => $company['supervisor'],
                'student_coc_date_issued'  => $submitted->copy()->subDays(2),
                'student_coc_receive_date' => $submitted,
                'student_coc_submitted_at' => $submitted,
                'faculty_coc_remarks'      => $remarks,
                'faculty_coc_reviewed_at'  => $reviewed,
            ],
            default => [],
        };

        return $row + $extra;
    }

    // ────────────────────────────────────────────────────────────
    // 5. INCIDENT REPORTS
    // ────────────────────────────────────────────────────────────
    private function seedIncidentReports(array $students): void
    {
        $enrolled = array_values(array_filter($students, fn ($s) => $s['section'] !== null));

        $incidents = [
            ['type' => 'Accident', 'status' => 'resolved', 'days' => 40,
             'location'    => 'Client office stairwell, 3rd floor',
             'description' => 'Slipped on a wet step while carrying equipment. Minor ankle sprain, treated at the company clinic.',
             'action'      => 'First aid given on site; sent home to rest for two days.',
             'remarks'     => 'Student has fully recovered and resumed duty. Company advised to post wet-floor signage.'],

            ['type' => 'Health Issue', 'status' => 'taken_action', 'days' => 22,
             'location'    => 'Host company workstation',
             'description' => 'Recurring migraines reported after extended screen time during a system migration crunch.',
             'action'      => 'Reported to supervisor and requested scheduled screen breaks.',
             'remarks'     => 'Coordinated with the supervisor to adjust the workload. Monitoring weekly.'],

            ['type' => 'Misconduct', 'status' => 'reviewing', 'days' => 12,
             'location'    => 'Host company pantry',
             'description' => 'Uncomfortable remarks made by a co-worker during break time.',
             'action'      => 'Kept a written record of the incident and informed the team lead.',
             'remarks'     => 'Meeting scheduled with the company HR officer.'],

            ['type' => 'Property Damage', 'status' => 'pending', 'days' => 6,
             'location'    => 'Company IT laboratory',
             'description' => 'A borrowed laptop charger was damaged while being unplugged from a faulty socket.',
             'action'      => 'Reported the damaged unit to the IT custodian immediately.',
             'remarks'     => null],

            ['type' => 'Security Concern', 'status' => 'pending', 'days' => 3,
             'location'    => 'Building parking area, late shift',
             'description' => 'Poor lighting in the parking area when leaving after the 8:00 PM shift.',
             'action'      => 'Asked to be scheduled out of late shifts until it is addressed.',
             'remarks'     => null],

            ['type' => 'Other', 'status' => 'declined', 'days' => 28,
             'location'    => 'Remote / work from home',
             'description' => 'Requested an excused absence for an unstable home internet connection.',
             'action'      => 'Notified the supervisor through the team chat.',
             'remarks'     => 'Filed under the wrong form — this belongs in the DTR remarks, not an incident report.'],
        ];

        // Walk the roster in strides so the incidents land in different sections.
        $stride = max(1, intdiv(count($enrolled), count($incidents)));

        foreach ($incidents as $index => $incident) {
            $student = $enrolled[($index * $stride) % count($enrolled)];
            $date    = now()->subDays($incident['days']);

            IncidentReport::create([
                'student_id'          => $student['user']->id,
                'section_id'          => $student['section']->id,
                'type'                => $incident['type'],
                'incident_date'       => $date->copy()->subDay(),
                'location'            => $incident['location'],
                'description'         => $incident['description'],
                'action_taken'        => $incident['action'],
                'faculty_status'      => $incident['status'],
                'faculty_remarks'     => $incident['remarks'],
                'faculty_reviewed_at' => $incident['status'] === 'pending' ? null : $date->copy()->addDay(),
                'created_at'          => $date,
                'updated_at'          => $date,
            ]);
        }

        $this->command->info('Incident reports: 6 (2 pending, 1 reviewing, 1 acted on, 1 resolved, 1 declined)');
    }

    // ────────────────────────────────────────────────────────────
    // 6. ANNOUNCEMENTS
    // ────────────────────────────────────────────────────────────
    private function seedAnnouncements(User $admin, User $faculty): array
    {
        $items = [
            ['days' => 2, 'by' => $admin->id, 'status' => 'active',
             'title' => 'OJT Orientation for Second Term Interns',
             'content' => "All students scheduled for deployment this term must attend the mandatory OJT orientation.\n\nDate: Friday, 8:00 AM\nVenue: AVR 2, Main Building\n\nBring your endorsement letter and a printed copy of your class schedule. Attendance will be checked and forms part of your pre-deployment requirements."],

            ['days' => 9, 'by' => $admin->id, 'status' => 'active',
             'title' => 'Deadline: Medical Records and Waivers',
             'content' => "Medical records and signed waivers are due at the end of the month.\n\nSubmissions made after the deadline will delay your endorsement to the host company. Upload the scanned copies through your checklist page — clear, readable scans only, as blurred files will be declined."],

            ['days' => 16, 'by' => $faculty->id, 'status' => 'active',
             'title' => 'Weekly Report Template Updated',
             'content' => "The weekly report template has been revised to include a short reflection section.\n\nStarting next week, use the updated template for all submissions. Reports using the old format will still be accepted this week only."],

            ['days' => 30, 'by' => $admin->id, 'status' => 'active',
             'title' => 'Reminder: Log Your DTR Hours Weekly',
             'content' => "Please encode your daily time record at the end of every week rather than in bulk at the end of the month.\n\nWeekly encoding lets your faculty catch discrepancies early and keeps your total hours accurate against the 486-hour requirement."],

            ['days' => 75, 'by' => $admin->id, 'status' => 'inactive',
             'title' => 'First Term Completion Ceremony (Concluded)',
             'content' => "The completion ceremony for first term interns was held last month. Certificates have been released through the OJT Program Office."],
        ];

        $announcements = [];

        foreach ($items as $item) {
            $announcement = Announcement::create([
                'title'      => $item['title'],
                'content'    => $item['content'],
                'status'     => $item['status'],
                'created_by' => $item['by'],
            ]);

            $date = now()->subDays($item['days']);
            DB::table('announcements')->where('id', $announcement->id)
                ->update(['created_at' => $date, 'updated_at' => $date]);

            $announcements[] = $announcement;
        }

        $this->command->info('Announcements: 5 (4 active, 1 inactive)');

        return $announcements;
    }

    private function seedAnnouncementReads(array $announcements, array $students): void
    {
        $reads = 0;

        foreach ($students as $index => $student) {
            // Older announcements have been seen by more people than recent ones.
            foreach ($announcements as $position => $announcement) {
                if ($index % ($position + 2) !== 0) {
                    continue;
                }

                AnnouncementRead::create([
                    'user_id'         => $student['user']->id,
                    'announcement_id' => $announcement->id,
                    'read_at'         => now()->subDays(rand(1, 20)),
                ]);
                $reads++;
            }
        }

        $this->command->info("Announcement reads: {$reads}");
    }

    // ────────────────────────────────────────────────────────────
    // 7. SUMMARY
    // ────────────────────────────────────────────────────────────
    private function summary(): void
    {
        $this->command->info('');
        $this->command->info('══════════════════════════════════════════════');
        $this->command->info('  SEEDING COMPLETE — every password is: password');
        $this->command->info('══════════════════════════════════════════════');
        $this->command->table(
            ['Role', 'Username', 'Notes'],
            [
                ['Admin',   'admin',      'Full system administrator'],
                ['Admin',   'tnavarro',   'Second admin account'],
                ['Faculty', 'mreyes',     'Handles BSIT-4A'],
                ['Faculty', 'jsantos',    'Handles BSIT-4B'],
                ['Faculty', 'lgomez',     'Handles BSIT-4C'],
                ['Faculty', 'rdizon',     'No section assigned'],
                ['Student', 'acruz',      'All requirements approved'],
                ['Student', 'mdelatorre', 'Advanced — some pending review'],
                ['Student', 'cmendoza',   'Midway — includes a declined item'],
                ['Student', 'rocampo',    'Just started'],
                ['Student', 'maquino',    'Inactive account'],
                ['Student', 'tmanalo',    'Not enrolled in any section'],
            ]
        );
        $this->command->info('  Full roster: ' . User::count() . ' users, '
            . Section::count() . ' sections, '
            . StudentChecklist::count() . ' submissions, '
            . IncidentReport::count() . ' incident reports.');
        $this->command->info('');
    }
}
