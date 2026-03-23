<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing', [
        'announcements' => collect([]),
        'cms' => [
            'about' => 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.',
            'mission' => 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.',
            'vision' => 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.',
            'contact_email' => 'ojtms@example.edu.ph',
            'contact_phone' => '+63 900 000 0000',
            'contact_address' => 'Sample City, Philippines',
        ]
    ]);
});

Route::get('/login', function () {
    return view('auth.login');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard', [
            'totalUsers' => 125,
            'admins' => 5,
            'faculty' => 30,
            'students' => 90,
        ]);
    });
    
    // Sections Routes
    Route::get('/sections', function () {
        return view('admin.sections.index', [
            'sections' => [
                ['id' => 1, 'code' => 'OJT-2025-001', 'school_year' => '2025-2026', 'term' => 'Term 1', 'day' => 'Monday', 'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Room 101', 'faculty' => 'Dr. Juan Dela Cruz', 'students_count' => 25],
                ['id' => 2, 'code' => 'OJT-2025-002', 'school_year' => '2025-2026', 'term' => 'Term 1', 'day' => 'Wednesday', 'start_time' => '13:00', 'end_time' => '17:00', 'room' => 'Room 102', 'faculty' => 'Prof. Maria Santos', 'students_count' => 28],
                ['id' => 3, 'code' => 'OJT-2025-003', 'school_year' => '2025-2026', 'term' => 'Term 2', 'day' => 'Friday', 'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Lab A', 'faculty' => 'Engr. Carlos Lopez', 'students_count' => 22],
            ]
        ]);
    });
    
    Route::get('/sections/create', function () {
        return view('admin.sections.create');
    });
    
    Route::post('/sections', function () {
        return redirect('/admin/sections')->with('success', 'Section created successfully!');
    });
    
    Route::get('/sections/{id}/edit', function ($id) {
        $sections = [
            1 => ['id' => 1, 'code' => 'OJT-2025-001', 'school_year' => '2025-2026', 'term' => 'Term 1', 'day' => 'Monday', 'start_time' => '08:00', 'end_time' => '12:00', 'room' => 'Room 101', 'faculty' => 'Dr. Juan Dela Cruz', 'faculty_id' => '1', 'capacity' => 30, 'days_count' => 1, 'description' => 'First batch OJT section', 'status' => 'Active'],
        ];
        $section = $sections[$id] ?? null;
        return view('admin.sections.create', ['section' => $section]);
    });
    
    Route::get('/sections/{id}/view', function ($id) {
        // View section details
        return view('admin.sections.view', ['section' => []]);
    });
    
    Route::get('/users', function () {
        return view('admin.users');
    });
    
    Route::get('/users/create', function () {
        return view('admin.users.create');
    });
    
    Route::get('/cms', function () {
        return view('admin.cms');
    });
    
    Route::get('/announcements', function () {
        return view('admin.announcements.index');
    });
    
    Route::get('/announcements/create', function () {
        return view('admin.announcements.create');
    });
    
    Route::get('/reports', function () {
        return view('admin.reports');
    });
});
