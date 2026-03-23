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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create sample sections
        Section::create([
            'name' => 'A1',
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
            'capacity' => null,
        ]);

        Section::create([
            'name' => 'A2',
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
            'capacity' => null,
        ]);

        Section::create([
            'name' => 'B1',
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
        ]);
    }
}
