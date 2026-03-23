<?php

use Illuminate\Support\Facades\Route;
use App\Models\Section;

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
        $sections = Section::all();
        return view('admin.sections.index', ['sections' => $sections]);
    });
    
    Route::get('/sections/create', function () {
        return view('admin.sections.create');
    });
    
    Route::post('/sections', function (\Illuminate\Http\Request $request) {
        Section::create([
            'name' => $request->input('section_name'),
            'school_year' => $request->input('school_year'),
            'term' => $request->input('term'),
            'day' => $request->input('day'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'days_count' => $request->input('days_count'),
            'room' => $request->input('room'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'faculty_id' => $request->input('faculty_id') ?: null,
            'capacity' => $request->input('capacity') ?: null,
        ]);
        return redirect('/admin/sections')->with('success', 'Section created successfully!');
    });
    
    Route::post('/sections/{id}', function ($id, \Illuminate\Http\Request $request) {
        $section = Section::findOrFail($id);
        $section->update([
            'name' => $request->input('section_name'),
            'school_year' => $request->input('school_year'),
            'term' => $request->input('term'),
            'day' => $request->input('day'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'days_count' => $request->input('days_count'),
            'room' => $request->input('room'),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
            'faculty_id' => $request->input('faculty_id') ?: null,
            'capacity' => $request->input('capacity') ?: null,
        ]);
        return redirect('/admin/sections')->with('success', 'Section updated successfully!');
    });
    
    Route::get('/sections/{id}/edit', function ($id) {
        $section = Section::findOrFail($id);
        return view('admin.sections.create', ['section' => $section]);
    });
    
    Route::get('/sections/{id}/view', function ($id) {
        $section = Section::findOrFail($id);
        return view('admin.sections.view', ['section' => $section]);
    });
    
    Route::delete('/sections/{id}', function ($id) {
        $section = Section::findOrFail($id);
        $section->delete();
        return redirect('/admin/sections')->with('success', 'Section deleted and archived!');
    });
    
    Route::get('/sections-archive', function () {
        $deletedSections = Section::onlyTrashed()->get();
        return view('admin.sections.archive', ['sections' => $deletedSections]);
    });
    
    Route::post('/sections/{id}/restore', function ($id) {
        $section = Section::withTrashed()->findOrFail($id);
        $section->restore();
        return redirect('/admin/sections-archive')->with('success', 'Section restored successfully!');
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
