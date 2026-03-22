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
