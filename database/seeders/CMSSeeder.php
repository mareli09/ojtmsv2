<?php

namespace Database\Seeders;

use App\Models\CMS;
use Illuminate\Database\Seeder;

class CMSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cmsSettings = [
            [
                'key' => 'header',
                'value' => 'AI-Assisted OJT Monitoring System',
                'section' => 'general',
            ],
            [
                'key' => 'subheader',
                'value' => 'Streamline On-the-Job Training Management with Intelligent Analytics',
                'section' => 'general',
            ],
            [
                'key' => 'about',
                'value' => 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.',
                'section' => 'general',
            ],
            [
                'key' => 'mission',
                'value' => 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.',
                'section' => 'general',
            ],
            [
                'key' => 'vision',
                'value' => 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.',
                'section' => 'general',
            ],
            [
                'key' => 'contact_email',
                'value' => 'ojtms@example.edu.ph',
                'section' => 'contact',
            ],
            [
                'key' => 'contact_phone',
                'value' => '+63 900 000 0000',
                'section' => 'contact',
            ],
            [
                'key' => 'contact_address',
                'value' => 'Sample City, Philippines',
                'section' => 'contact',
            ],
            [
                'key' => 'facebook_url',
                'value' => 'https://facebook.com',
                'section' => 'social_media',
            ],
            [
                'key' => 'instagram_url',
                'value' => 'https://instagram.com',
                'section' => 'social_media',
            ],
            [
                'key' => 'linkedin_url',
                'value' => 'https://linkedin.com',
                'section' => 'social_media',
            ],
            [
                'key' => 'twitter_url',
                'value' => 'https://twitter.com',
                'section' => 'social_media',
            ],
            [
                'key' => 'youtube_url',
                'value' => 'https://youtube.com',
                'section' => 'social_media',
            ],
        ];

        foreach ($cmsSettings as $setting) {
            CMS::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        \Log::info('CMS Settings seeded successfully');
    }
}
