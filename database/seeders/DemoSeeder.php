<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Section;
use App\Models\StudentChecklist;
use App\Models\Announcement;
use App\Models\Faq;
use App\Models\IncidentReport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoSeeder extends Seeder
{
    /**
     * DEMO SEEDER — Wipes all transactional data and seeds clean demo accounts.
     *
     * Run with:  php artisan db:seed --class=DemoSeeder
     */
    public function run(): void
    {
        // ──────────────────────────────────────────────
        // 1. WIPE ALL TRANSACTIONAL DATA
        // ──────────────────────────────────────────────
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('announcement_reads')->truncate();
        DB::table('student_checklists')->truncate();
        DB::table('incident_reports')->truncate();
        DB::table('announcements')->truncate();
        DB::table('faqs')->truncate();
        DB::table('users')->truncate();
        DB::table('sections')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->command->info('All transactional data cleared.');

        // ──────────────────────────────────────────────
        // 2. CMS SETTINGS (keep / re-seed)
        // ──────────────────────────────────────────────
        $this->call(CMSSeeder::class);

        // ──────────────────────────────────────────────
        // 3. SECTIONS
        // ──────────────────────────────────────────────
        $sectionA = Section::create([
            'name'        => 'BSIT-4A',
            'school_year' => '2025-2026',
            'term'        => 'Term 2',
            'day'         => 'Monday / Wednesday',
            'start_time'  => '08:00',
            'end_time'    => '12:00',
            'days_count'  => 2,
            'room'        => 'Room 301',
            'description' => 'Bachelor of Science in Information Technology — 4th Year Section A',
            'status'      => 'active',
            'faculty_id'  => null,
            'capacity'    => 35,
        ]);

        $sectionB = Section::create([
            'name'        => 'BSIT-4B',
            'school_year' => '2025-2026',
            'term'        => 'Term 2',
            'day'         => 'Tuesday / Thursday',
            'start_time'  => '13:00',
            'end_time'    => '17:00',
            'days_count'  => 2,
            'room'        => 'Room 302',
            'description' => 'Bachelor of Science in Information Technology — 4th Year Section B',
            'status'      => 'active',
            'faculty_id'  => null,
            'capacity'    => 35,
        ]);

        $this->command->info('Sections created: BSIT-4A, BSIT-4B');

        // ──────────────────────────────────────────────
        // 4. ADMIN ACCOUNT
        // ──────────────────────────────────────────────
        User::create([
            'employee_id' => 'ADM-001',
            'role'        => 'admin',
            'first_name'  => 'System',
            'last_name'   => 'Administrator',
            'username'    => 'admin',
            'email'       => 'admin@ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09100000001',
            'department'  => 'Administration',
            'section_id'  => null,
            'status'      => 'active',
        ]);

        $this->command->info('Admin created → username: admin | password: password');

        // ──────────────────────────────────────────────
        // 5. FACULTY ACCOUNTS
        // ──────────────────────────────────────────────
        $faculty1 = User::create([
            'employee_id' => 'FAC-001',
            'role'        => 'faculty',
            'first_name'  => 'Maria',
            'middle_name' => 'L',
            'last_name'   => 'Reyes',
            'username'    => 'mreyes',
            'email'       => 'mreyes@ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09100000002',
            'department'  => 'IT Department',
            'section_id'  => $sectionA->id,
            'status'      => 'active',
        ]);

        $faculty2 = User::create([
            'employee_id' => 'FAC-002',
            'role'        => 'faculty',
            'first_name'  => 'Jose',
            'middle_name' => 'P',
            'last_name'   => 'Santos',
            'username'    => 'jsantos',
            'email'       => 'jsantos@ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09100000003',
            'department'  => 'IT Department',
            'section_id'  => $sectionB->id,
            'status'      => 'active',
        ]);

        $sectionA->update(['faculty_id' => $faculty1->id]);
        $sectionB->update(['faculty_id' => $faculty2->id]);

        $this->command->info('Faculty created → mreyes (Section A) | jsantos (Section B) | password: password');

        // ──────────────────────────────────────────────
        // 6. STUDENT ACCOUNTS
        // ──────────────────────────────────────────────

        // Student 1 — CLEAN (no submissions, ready to demo full workflow)
        $student1 = User::create([
            'student_id'  => 'STU-2526-001',
            'role'        => 'student',
            'first_name'  => 'Anna',
            'middle_name' => 'R',
            'last_name'   => 'Cruz',
            'username'    => 'acruz',
            'email'       => 'acruz@student.ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09200000001',
            'department'  => 'IT Department',
            'section_id'  => $sectionA->id,
            'status'      => 'active',
        ]);

        // Student 2 — IN PROGRESS (some approved, some pending)
        $student2 = User::create([
            'student_id'  => 'STU-2526-002',
            'role'        => 'student',
            'first_name'  => 'Miguel',
            'middle_name' => 'A',
            'last_name'   => 'Dela Torre',
            'username'    => 'mdelatorre',
            'email'       => 'mdelatorre@student.ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09200000002',
            'department'  => 'IT Department',
            'section_id'  => $sectionA->id,
            'status'      => 'active',
        ]);

        // Student 3 — ADVANCED (most items done, pending faculty review on latest)
        $student3 = User::create([
            'student_id'  => 'STU-2526-003',
            'role'        => 'student',
            'first_name'  => 'Sofia',
            'middle_name' => 'M',
            'last_name'   => 'Villanueva',
            'username'    => 'svillanueva',
            'email'       => 'svillanueva@student.ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09200000003',
            'department'  => 'IT Department',
            'section_id'  => $sectionB->id,
            'status'      => 'active',
        ]);

        // Student 4 — SECTION B clean student
        $student4 = User::create([
            'student_id'  => 'STU-2526-004',
            'role'        => 'student',
            'first_name'  => 'Carlos',
            'middle_name' => 'J',
            'last_name'   => 'Mendoza',
            'username'    => 'cmendoza',
            'email'       => 'cmendoza@student.ojtms.edu.ph',
            'password'    => bcrypt('password'),
            'contact'     => '09200000004',
            'department'  => 'IT Department',
            'section_id'  => $sectionB->id,
            'status'      => 'active',
        ]);

        $this->command->info('Students created: acruz, mdelatorre, svillanueva, cmendoza | password: password');

        // ──────────────────────────────────────────────
        // 7. CHECKLIST DATA FOR STUDENT 2 (In Progress)
        //    Medical Record      → APPROVED
        //    Receipt of OJT Kit  → APPROVED
        //    Waiver           → PENDING (submitted, awaiting review)
        //    Endorsement letter → submitted (pending)
        //    DTR              → 2 entries (1 approved, 1 pending)
        //    Weekly report    → 1 entry pending
        // ──────────────────────────────────────────────
        $sid  = $sectionA->id;
        $uid2 = $student2->id;

        // Medical Record — APPROVED
        StudentChecklist::create([
            'section_id'          => $sid,
            'student_id'          => $uid2,
            'item'                => 'Medical Record',
            'student_clinic_name' => 'MSTIP Clinic',
            'student_clinic_address' => 'Makati City',
            'student_encoded_at'  => now()->subDays(20),
            'student_submitted_at'=> now()->subDays(20),
            'faculty_status'      => 'approved',
            'faculty_remarks'     => 'Complete. Good to go.',
            'faculty_reviewed_at' => now()->subDays(18),
        ]);

        // Receipt of OJT Kit — APPROVED
        StudentChecklist::create([
            'section_id'           => $sid,
            'student_id'           => $uid2,
            'item'                 => 'Receipt of OJT Kit',
            'student_receipt_number' => 'OR-2526-0042',
            'student_paid_date'    => now()->subDays(19),
            'student_encoded_at'   => now()->subDays(19),
            'student_submitted_at' => now()->subDays(19),
            'faculty_status'       => 'approved',
            'faculty_remarks'      => 'Receipt number confirmed.',
            'faculty_reviewed_at'  => now()->subDays(17),
        ]);

        // Waiver — PENDING (submitted, waiting faculty review)
        StudentChecklist::create([
            'section_id'                => $sid,
            'student_id'                => $uid2,
            'item'                      => 'Waiver',
            'student_guardian_name'     => 'Ricardo Dela Torre',
            'student_guardian_contact'  => '09300000001',
            'student_guardian_email'    => 'rdelatorre@email.com',
            'student_guardian_social'   => 'N/A',
            'student_encoded_at'        => now()->subDays(10),
            'student_submitted_at'      => now()->subDays(10),
            'faculty_status'            => 'pending',
        ]);

        // Endorsement letter — PENDING
        StudentChecklist::create([
            'section_id'               => $sid,
            'student_id'               => $uid2,
            'item'                     => 'Endorsement letter',
            'student_endorsement_date' => now()->subDays(8),
            'student_start_date'       => now()->subDays(7),
            'student_encoded_at'       => now()->subDays(8),
            'student_submitted_at'     => now()->subDays(8),
            'student_remarks'          => 'Endorsed to ABC Tech Company, Makati City.',
            'faculty_status'           => 'pending',
        ]);

        // DTR — Week 1 (APPROVED)
        StudentChecklist::create([
            'section_id'               => $sid,
            'student_id'               => $uid2,
            'item'                     => 'DTR',
            'student_dtr_week'         => 'Week 1 — January 2026',
            'student_dtr_hours'        => 40,
            'student_dtr_validated_by' => 'Mr. John Supervisor',
            'student_submitted_at'     => now()->subDays(14),
            'faculty_status'           => 'approved',
            'faculty_remarks'          => 'Verified. 40 hours counted.',
            'faculty_dtr_target_hours' => 720,
            'faculty_dtr_reviewed_at'  => now()->subDays(12),
        ]);

        // DTR — Week 2 (PENDING)
        StudentChecklist::create([
            'section_id'               => $sid,
            'student_id'               => $uid2,
            'item'                     => 'DTR',
            'student_dtr_week'         => 'Week 2 — January 2026',
            'student_dtr_hours'        => 40,
            'student_dtr_validated_by' => 'Mr. John Supervisor',
            'student_submitted_at'     => now()->subDays(5),
            'faculty_status'           => 'pending',
        ]);

        // Weekly report — Week 1 (PENDING)
        StudentChecklist::create([
            'section_id'                        => $sid,
            'student_id'                        => $uid2,
            'item'                              => 'Weekly report',
            'student_weekly_week'               => 'Week 1 — January 2026',
            'student_weekly_task_description'   => 'Assisted in front-end development using React.js. Attended orientation and team standup meetings. Helped debug UI components assigned by the team lead.',
            'student_weekly_supervisor_feedback'=> 'Good progress. Showed initiative.',
            'student_weekly_submitted_at'       => now()->subDays(6),
            'faculty_status'                    => 'pending',
        ]);

        $this->command->info('Student 2 (mdelatorre) checklist seeded — In Progress state.');

        // ──────────────────────────────────────────────
        // 8. CHECKLIST DATA FOR STUDENT 3 (Advanced)
        //    First 5 items ALL APPROVED
        //    4 DTR entries (3 approved, 1 pending)
        //    3 Weekly reports (2 approved, 1 pending)
        //    1 Monthly appraisal (pending)
        //    Supervisor evaluation (pending)
        // ──────────────────────────────────────────────
        $sid3 = $sectionB->id;
        $uid3 = $student3->id;

        // Medical Record — APPROVED
        StudentChecklist::create([
            'section_id'           => $sid3,
            'student_id'           => $uid3,
            'item'                 => 'Medical Record',
            'student_clinic_name'  => 'City Health Center',
            'student_clinic_address' => 'Makati City',
            'student_encoded_at'   => now()->subDays(55),
            'student_submitted_at' => now()->subDays(55),
            'faculty_status'       => 'approved',
            'faculty_reviewed_at'  => now()->subDays(53),
        ]);

        // Receipt of OJT Kit — APPROVED
        StudentChecklist::create([
            'section_id'             => $sid3,
            'student_id'             => $uid3,
            'item'                   => 'Receipt of OJT Kit',
            'student_receipt_number' => 'OR-2526-0017',
            'student_paid_date'      => now()->subDays(54),
            'student_encoded_at'     => now()->subDays(54),
            'student_submitted_at'   => now()->subDays(54),
            'faculty_status'         => 'approved',
            'faculty_reviewed_at'    => now()->subDays(52),
        ]);

        // Waiver — APPROVED
        StudentChecklist::create([
            'section_id'               => $sid3,
            'student_id'               => $uid3,
            'item'                     => 'Waiver',
            'student_guardian_name'    => 'Luisa Villanueva',
            'student_guardian_contact' => '09300000002',
            'student_guardian_email'   => 'lvillanueva@email.com',
            'student_encoded_at'       => now()->subDays(50),
            'student_submitted_at'     => now()->subDays(50),
            'faculty_status'           => 'approved',
            'faculty_reviewed_at'      => now()->subDays(48),
        ]);

        // Endorsement letter — APPROVED
        StudentChecklist::create([
            'section_id'               => $sid3,
            'student_id'               => $uid3,
            'item'                     => 'Endorsement letter',
            'student_endorsement_date' => now()->subDays(48),
            'student_start_date'       => now()->subDays(45),
            'student_encoded_at'       => now()->subDays(48),
            'student_submitted_at'     => now()->subDays(48),
            'faculty_status'           => 'approved',
            'faculty_reviewed_at'      => now()->subDays(46),
        ]);

        // MOA — APPROVED
        StudentChecklist::create([
            'section_id'           => $sid3,
            'student_id'           => $uid3,
            'item'                 => 'MOA',
            'student_encoded_at'   => now()->subDays(44),
            'student_submitted_at' => now()->subDays(44),
            'student_remarks'      => 'MOA signed by XYZ Solutions Inc.',
            'faculty_status'       => 'approved',
            'faculty_reviewed_at'  => now()->subDays(42),
        ]);

        // DTR — Weeks 1-3 APPROVED, Week 4 PENDING
        $dtrWeeks = [
            ['week' => 'Week 1 — November 2025', 'days' => 42, 'approved' => true],
            ['week' => 'Week 2 — November 2025', 'days' => 35, 'approved' => true],
            ['week' => 'Week 3 — December 2025', 'days' => 28, 'approved' => true],
            ['week' => 'Week 4 — January 2026',  'days' => 5,  'approved' => false],
        ];
        foreach ($dtrWeeks as $dtr) {
            StudentChecklist::create([
                'section_id'               => $sid3,
                'student_id'               => $uid3,
                'item'                     => 'DTR',
                'student_dtr_week'         => $dtr['week'],
                'student_dtr_hours'        => 40,
                'student_dtr_validated_by' => 'Ms. Ana Torres',
                'student_submitted_at'     => now()->subDays($dtr['days']),
                'faculty_status'           => $dtr['approved'] ? 'approved' : 'pending',
                'faculty_remarks'          => $dtr['approved'] ? '40 hours approved.' : null,
                'faculty_dtr_target_hours' => 720,
                'faculty_dtr_reviewed_at'  => $dtr['approved'] ? now()->subDays($dtr['days'] - 2) : null,
            ]);
        }

        // Weekly reports — 2 APPROVED, 1 PENDING
        $weeklyReports = [
            ['week' => 'Week 1 — November 2025', 'days' => 40, 'approved' => true],
            ['week' => 'Week 2 — November 2025', 'days' => 33, 'approved' => true],
            ['week' => 'Week 3 — December 2025', 'days' => 4,  'approved' => false],
        ];
        foreach ($weeklyReports as $wr) {
            StudentChecklist::create([
                'section_id'                       => $sid3,
                'student_id'                       => $uid3,
                'item'                             => 'Weekly report',
                'student_weekly_week'              => $wr['week'],
                'student_weekly_task_description'  => 'Worked on assigned tasks including system testing, documentation, and backend API development.',
                'student_weekly_supervisor_feedback'=> 'Performed well this week.',
                'student_weekly_submitted_at'      => now()->subDays($wr['days']),
                'faculty_status'                   => $wr['approved'] ? 'approved' : 'pending',
                'faculty_weekly_remarks'           => $wr['approved'] ? 'Report reviewed and accepted.' : null,
                'faculty_weekly_reviewed_at'       => $wr['approved'] ? now()->subDays($wr['days'] - 2) : null,
            ]);
        }

        // Monthly appraisal — PENDING
        StudentChecklist::create([
            'section_id'                    => $sid3,
            'student_id'                    => $uid3,
            'item'                          => 'Monthly appraisal',
            'student_appraisal_month'       => 'November 2025',
            'student_appraisal_grade_rating'=> '90',
            'student_appraisal_evaluated_by'=> 'Ms. Ana Torres',
            'student_appraisal_submitted_at'=> now()->subDays(6),
            'faculty_status'                => 'pending',
        ]);

        // Supervisor evaluation — PENDING
        StudentChecklist::create([
            'section_id'                         => $sid3,
            'student_id'                         => $uid3,
            'item'                               => 'Supervisor evaluation',
            'student_supervisor_eval_grade'      => '88',
            'student_supervisor_eval_submitted_at' => now()->subDays(3),
            'faculty_status'                     => 'pending',
        ]);

        $this->command->info('Student 3 (svillanueva) checklist seeded — Advanced state.');

        // ──────────────────────────────────────────────
        // 9. SAMPLE ANNOUNCEMENTS
        // ──────────────────────────────────────────────
        $adminId = \App\Models\User::where('username', 'admin')->value('id');

        Announcement::create([
            'title'      => 'Welcome to the OJT Monitoring System',
            'content'    => 'Dear students and faculty, welcome to the AI-Assisted OJT Monitoring System. Please complete your profile and begin submitting your required OJT documents through the Checklist section. For questions, use the AI Chatbot available in your dashboard.',
            'created_by' => $adminId,
            'status'     => 'active',
        ]);

        Announcement::create([
            'title'      => 'Reminder: DTR Submission Deadline',
            'content'    => 'All students are reminded to submit their Daily Time Records every end of the week. Failure to submit on time may affect your OJT credit hours. Coordinate with your supervisor for proper validation before submission.',
            'created_by' => $adminId,
            'status'     => 'active',
        ]);

        Announcement::create([
            'title'      => 'OJT Orientation Schedule',
            'content'    => 'An OJT orientation will be held this coming Friday. All 4th year IT students are required to attend. Details will be sent through the official school email. Please check this platform regularly for updates.',
            'created_by' => $adminId,
            'status'     => 'active',
        ]);

        $this->command->info('Announcements seeded.');

        // ──────────────────────────────────────────────
        // 10. SAMPLE FAQs
        // ──────────────────────────────────────────────
        $this->call(FaqSeeder::class);
        $this->command->info('FAQs seeded.');

        // ──────────────────────────────────────────────
        // SUMMARY
        // ──────────────────────────────────────────────
        $this->command->info('');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  DEMO SEEDING COMPLETE — All passwords: password');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  ADMIN    → username: admin');
        $this->command->info('  FACULTY  → username: mreyes   (Section BSIT-4A)');
        $this->command->info('  FACULTY  → username: jsantos  (Section BSIT-4B)');
        $this->command->info('  STUDENT  → username: acruz        [CLEAN — no submissions]');
        $this->command->info('  STUDENT  → username: mdelatorre   [IN PROGRESS — partial checklist]');
        $this->command->info('  STUDENT  → username: svillanueva  [ADVANCED — most items done]');
        $this->command->info('  STUDENT  → username: cmendoza     [CLEAN — no submissions]');
        $this->command->info('══════════════════════════════════════════');
    }
}
