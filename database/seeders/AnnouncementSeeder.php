<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get an admin user to associate with announcements
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        $announcements = [
            [
                'title' => 'OJT Monitoring System Launch',
                'content' => 'We are excited to announce the official launch of the AI-Assisted OJT Monitoring System. This innovative platform will revolutionize how we manage on-the-job training programs. All students, faculty, and staff are now registered and can access the system using their credentials. Welcome aboard!',
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'title' => 'Updated Attendance Tracking Feature',
                'content' => 'We have implemented an enhanced attendance tracking system with real-time analytics. Faculty members can now generate comprehensive attendance reports with a single click. Students can view their attendance records and any discrepancies. Please review the updated guidelines in the system documentation.',
                'status' => 'active',
                'created_by' => $adminId,
            ],
            [
                'title' => 'Scheduled System Maintenance',
                'content' => 'The OJTMS system will undergo scheduled maintenance on March 30, 2026 from 10:00 PM to 12:00 AM (PST). During this time, the system will be temporarily unavailable. We apologize for any inconvenience and appreciate your patience as we work to improve our services.',
                'status' => 'active',
                'created_by' => $adminId,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }

        \Log::info('Announcements seeded successfully');
    }
}
