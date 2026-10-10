<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Delegates to FullDemoSeeder, which wipes the transactional tables and
     * rebuilds a complete dataset: CMS settings, sections, users of every role,
     * checklist submissions in every review state, incident reports,
     * announcements with read receipts, and FAQs.
     *
     *   php artisan migrate:fresh --seed
     *   php artisan db:seed
     *
     * Every account is created with the password: password
     */
    public function run(): void
    {
        $this->call(FullDemoSeeder::class);
    }
}
