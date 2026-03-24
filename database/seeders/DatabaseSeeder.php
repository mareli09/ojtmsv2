<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Section;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed CMS Settings
        $this->call(CMSSeeder::class);
        
        // Step 1: Create sample sections (initially without faculty)
        $section1 = Section::updateOrCreate(
            ['name' => 'A1'],
            [
                'school_year' => '2025-2026',
                'term' => 'Term 1',
                'day' => 'Monday',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'days_count' => 1,
                'room' => 'Room 101',
                'description' => 'First batch OJT section for IT students',
                'status' => 'active',
                'faculty_id' => null,
                'capacity' => 30,
            ]
        );

        $section2 = Section::updateOrCreate(
            ['name' => 'A2'],
            [
                'school_year' => '2025-2026',
                'term' => 'Term 1',
                'day' => 'Wednesday',
                'start_time' => '13:00',
                'end_time' => '17:00',
                'days_count' => 1,
                'room' => 'Room 102',
                'description' => 'Second batch OJT section',
                'status' => 'active',
                'faculty_id' => null,
                'capacity' => 30,
            ]
        );

        $section3 = Section::updateOrCreate(
            ['name' => 'B1'],
            [
                'school_year' => '2025-2026',
                'term' => 'Term 2',
                'day' => 'Friday',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'days_count' => 1,
                'room' => 'Lab A',
                'description' => 'Laboratory-based OJT',
                'status' => 'active',
                'faculty_id' => null,
                'capacity' => 30,
            ]
        );

        $section4 = Section::updateOrCreate(
            ['name' => 'C1'],
            [
                'school_year' => '2025-2026',
                'term' => 'Term 1',
                'day' => 'Tuesday',
                'start_time' => '08:00',
                'end_time' => '12:00',
                'days_count' => 1,
                'room' => 'Room 201',
                'description' => 'Engineering track OJT',
                'status' => 'active',
                'faculty_id' => null,
                'capacity' => 25,
            ]
        );

        // Step 2: Create admin user
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'employee_id' => 'ADM001',
                'role' => 'admin',
                'first_name' => 'Admin',
                'last_name' => 'User',
                'username' => 'admin',
                'password' => bcrypt('password'),
                'contact' => '0912345678',
                'department' => 'Administration',
                'section_id' => null,
                'status' => 'active',
            ]
        );
        
        \Log::info('Test Account Created - Admin: admin / password');

        // Step 3: Create faculty users and assign them to sections (one faculty per section)
        $faculty1 = User::updateOrCreate(
            ['email' => 'juan@example.com'],
            [
                'employee_id' => 'FAC001',
                'role' => 'faculty',
                'first_name' => 'Dr. Juan',
                'middle_name' => 'M',
                'last_name' => 'Dela Cruz',
                'username' => 'juandelacru',
                'password' => bcrypt('password'),
                'contact' => '0912345679',
                'department' => 'IT',
                'section_id' => $section1->id,
                'status' => 'active',
            ]
        );
        \Log::info('Test Account Created - Faculty 1: juandelacru / password');

        $faculty2 = User::updateOrCreate(
            ['email' => 'maria@example.com'],
            [
                'employee_id' => 'FAC002',
                'role' => 'faculty',
                'first_name' => 'Prof. Maria',
                'last_name' => 'Santos',
                'username' => 'mariasantos',
                'password' => bcrypt('password'),
                'contact' => '0912345680',
                'department' => 'IT',
                'section_id' => $section2->id,
                'status' => 'active',
            ]
        );
        \Log::info('Test Account Created - Faculty 2: mariasantos / password');

        $faculty3 = User::updateOrCreate(
            ['email' => 'carlos@example.com'],
            [
                'employee_id' => 'FAC003',
                'role' => 'faculty',
                'first_name' => 'Engr. Carlos',
                'last_name' => 'Lopez',
                'username' => 'carloslopez',
                'password' => bcrypt('password'),
                'contact' => '0912345681',
                'department' => 'Engineering',
                'section_id' => $section3->id,
                'status' => 'active',
            ]
        );
        \Log::info('Test Account Created - Faculty 3: carloslopez / password');

        $faculty4 = User::updateOrCreate(
            ['email' => 'ruth@example.com'],
            [
                'employee_id' => 'FAC004',
                'role' => 'faculty',
                'first_name' => 'Dr. Ruth',
                'last_name' => 'Gonzales',
                'username' => 'ruthgonzales',
                'password' => bcrypt('password'),
                'contact' => '0912345682',
                'department' => 'Engineering',
                'section_id' => $section4->id,
                'status' => 'active',
            ]
        );
        \Log::info('Test Account Created - Faculty 4: ruthgonzales / password');

        // Step 4: Update sections to link to faculty (inverse relationship)
        $section1->update(['faculty_id' => $faculty1->id]);
        $section2->update(['faculty_id' => $faculty2->id]);
        $section3->update(['faculty_id' => $faculty3->id]);
        $section4->update(['faculty_id' => $faculty4->id]);

        // Step 5: Create student users
        User::updateOrCreate(
            ['email' => 'john@student.com'],
            [
                'student_id' => 'STU001',
                'role' => 'student',
                'first_name' => 'John',
                'middle_name' => 'Paul',
                'last_name' => 'Smith',
                'username' => 'johnsmith',
                'password' => bcrypt('password'),
                'contact' => '0912345683',
                'department' => 'IT',
                'section_id' => $section1->id,
                'status' => 'active',
            ]
        );
        \Log::info('Test Account Created - Student 1: johnsmith / password');

        User::updateOrCreate(
            ['email' => 'jane@student.com'],
            [
                'student_id' => 'STU002',
                'role' => 'student',
                'first_name' => 'Jane',
                'last_name' => 'Doe',
                'username' => 'janedoe',
                'password' => bcrypt('password'),
                'contact' => '0912345684',
                'department' => 'IT',
                'section_id' => $section1->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'michael@student.com'],
            [
                'student_id' => 'STU003',
                'role' => 'student',
                'first_name' => 'Michael',
                'middle_name' => 'James',
                'last_name' => 'Anderson',
                'username' => 'michaelands',
                'password' => bcrypt('password'),
                'contact' => '0912345685',
                'department' => 'IT',
                'section_id' => $section2->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'sarah@student.com'],
            [
                'student_id' => 'STU004',
                'role' => 'student',
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'username' => 'sarahwilson',
                'password' => bcrypt('password'),
                'contact' => '0912345686',
                'department' => 'IT',
                'section_id' => $section2->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'robert@student.com'],
            [
                'student_id' => 'STU005',
                'role' => 'student',
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'username' => 'robertbrown',
                'password' => bcrypt('password'),
                'contact' => '0912345687',
                'department' => 'Engineering',
                'section_id' => $section3->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'emily@student.com'],
            [
                'student_id' => 'STU006',
                'role' => 'student',
                'first_name' => 'Emily',
                'last_name' => 'Davis',
                'username' => 'emilydavis',
                'password' => bcrypt('password'),
                'contact' => '0912345688',
                'department' => 'Engineering',
                'section_id' => $section3->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'david@student.com'],
            [
                'student_id' => 'STU007',
                'role' => 'student',
                'first_name' => 'David',
                'last_name' => 'Miller',
                'username' => 'davidmiller',
                'password' => bcrypt('password'),
                'contact' => '0912345689',
                'department' => 'Engineering',
                'section_id' => $section4->id,
                'status' => 'active',
            ]
        );

        User::updateOrCreate(
            ['email' => 'rachel@student.com'],
            [
                'student_id' => 'STU008',
                'role' => 'student',
                'first_name' => 'Rachel',
                'last_name' => 'Taylor',
                'username' => 'racheltaylor',
                'password' => bcrypt('password'),
                'contact' => '0912345690',
                'department' => 'Engineering',
                'section_id' => $section4->id,
                'status' => 'active',
            ]
        );
        
        // Seed Announcements
        $this->call(AnnouncementSeeder::class);

        // Seed FAQs
        $this->call(FaqSeeder::class);
        
        // Summary of test accounts
        \Log::info('======== DATABASE SEEDING COMPLETE ========');
        \Log::info('All test accounts have been created with password: "password"');
        \Log::info('=======================================');
    }
}
