<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Faq;
use App\Models\User;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faculty = User::where('role', 'faculty')->first();
        if (!$faculty) return;

        $faqs = [
            [
                'category' => 'DTR & Attendance',
                'question' => 'How do I submit my Daily Time Record (DTR)?',
                'answer'   => 'Go to your Checklist, select "Daily Time Record", then click "Submit New DTR". Fill in the week covered, total hours worked, and supervisor name, then upload your signed DTR file. Click Submit and wait for faculty review.',
            ],
            [
                'category' => 'DTR & Attendance',
                'question' => 'What happens if my DTR is declined?',
                'answer'   => 'If your DTR is declined, check the faculty remarks on your DTR entry for the specific reason. Correct the issue (e.g., missing signature, incorrect hours) and resubmit a revised DTR.',
            ],
            [
                'category' => 'DTR & Attendance',
                'question' => 'How many hours are required to complete OJT?',
                'answer'   => 'The required OJT hours depend on your program. Your faculty will set the target hours on your DTR checklist. Typically, IT students are required to complete 486 hours. Check with your faculty for your specific requirement.',
            ],
            [
                'category' => 'Documents & Submissions',
                'question' => 'What documents do I need to complete my OJT checklist?',
                'answer'   => 'You need to submit the following: Medical Record, Receipt of OJT Kit, Waiver, Endorsement Letter, MOA (Memorandum of Agreement), DTR (weekly), Weekly Report, Monthly Appraisal, Supervisor Evaluation, and Certificate of Completion.',
            ],
            [
                'category' => 'Documents & Submissions',
                'question' => 'How do I submit my Weekly Report?',
                'answer'   => 'Go to the Weekly Report section from the sidebar. Click "Submit New Weekly Report", fill in the week details and accomplishments, upload your weekly report file, and submit. Your faculty will review it.',
            ],
            [
                'category' => 'Documents & Submissions',
                'question' => 'Can I edit a submission after it has been submitted?',
                'answer'   => 'You can edit a submission only while its status is "Pending" (not yet reviewed by faculty). Once the faculty has started reviewing or approved/declined it, editing is no longer allowed.',
            ],
            [
                'category' => 'Reviews & Status',
                'question' => 'How long does faculty review take?',
                'answer'   => 'Reviews are typically processed within 1–3 working days. You will see the status updated on your checklist page. If it has been more than 3 days, you may reach out to your assigned faculty.',
            ],
            [
                'category' => 'Reviews & Status',
                'question' => 'What do the submission statuses mean?',
                'answer'   => '"Pending" means your submission is awaiting faculty review. "Approved" means it has been accepted. "Declined" means there is an issue — check faculty remarks and resubmit. "Revision Needed" means minor corrections are required.',
            ],
            [
                'category' => 'Account & Profile',
                'question' => 'How do I update my profile or contact information?',
                'answer'   => 'Go to Profile Settings from the sidebar. You can update your email address, contact number, and password. Make sure your contact information is always up to date for important announcements.',
            ],
            [
                'category' => 'Account & Profile',
                'question' => 'I forgot my password. What should I do?',
                'answer'   => 'Contact your faculty or the system administrator to have your password reset. You can also reach out via the Incident Report feature for urgent account issues.',
            ],
            [
                'category' => 'Incident Reports',
                'question' => 'When should I file an Incident Report?',
                'answer'   => 'File an Incident Report for any unusual or serious situation that occurred during your OJT — such as accidents, workplace misconduct, health issues, property damage, or security concerns. You can attach evidence files to your report.',
            ],
            [
                'category' => 'Incident Reports',
                'question' => 'What happens after I submit an Incident Report?',
                'answer'   => 'Your faculty will receive and review the report. They will update the status to "Reviewing", "Action Taken", "Resolved", or "Declined" and may provide remarks. You can monitor the status from your Incident Report page.',
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::firstOrCreate(
                ['question' => $faq['question']],
                array_merge($faq, ['faculty_id' => $faculty->id])
            );
        }
    }
}
