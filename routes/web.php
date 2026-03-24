<?php

use Illuminate\Support\Facades\Route;
use App\Models\Section;

Route::get('/', function () {
    $cms = \App\Models\CMS::all()->keyBy('key');
    $announcements = \App\Models\Announcement::active()->latest()->get();
    
    return view('landing', [
        'announcements' => $announcements,
        'cms' => [
            'header' => $cms->get('header')->value ?? 'AI-Assisted OJT Monitoring System',
            'subheader' => $cms->get('subheader')->value ?? 'Streamline On-the-Job Training Management with Intelligent Analytics',
            'about' => $cms->get('about')->value ?? 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.',
            'mission' => $cms->get('mission')->value ?? 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.',
            'vision' => $cms->get('vision')->value ?? 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.',
            'contact_email' => $cms->get('contact_email')->value ?? 'ojtms@example.edu.ph',
            'contact_phone' => $cms->get('contact_phone')->value ?? '+63 900 000 0000',
            'contact_address' => $cms->get('contact_address')->value ?? 'Sample City, Philippines',
            'facebook_url' => $cms->get('facebook_url')->value ?? 'https://facebook.com',
            'instagram_url' => $cms->get('instagram_url')->value ?? 'https://instagram.com',
            'linkedin_url' => $cms->get('linkedin_url')->value ?? 'https://linkedin.com',
            'twitter_url' => $cms->get('twitter_url')->value ?? 'https://twitter.com',
            'youtube_url' => $cms->get('youtube_url')->value ?? 'https://youtube.com',
        ]
    ]);
});

Route::get('/login', function () {
    // If already logged in, redirect to appropriate dashboard
    if (session()->has('user_id') && session()->has('user')) {
        $user = session('user');
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'faculty') {
            return redirect('/faculty/dashboard');
        } else {
            return redirect('/student/dashboard');
        }
    }
    
    // Fetch one test account per role
    $testAccounts = collect(['admin', 'faculty', 'student'])->map(function ($role) {
        return \App\Models\User::where('role', $role)->where('status', 'active')->first();
    })->filter();
    
    return view('auth.login', ['testAccounts' => $testAccounts]);
});

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = \App\Models\User::where('username', $request->username)->first();

    if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        session(['user_id' => $user->id, 'user' => $user, 'user_role' => $user->role]);
        
        // Redirect based on role
        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        } elseif ($user->role === 'faculty') {
            return redirect('/faculty/dashboard');
        } else {
            return redirect('/student/dashboard');
        }
    }

    return back()->withErrors(['username' => 'Invalid username or password.']);
})->name('login.post');

Route::get('/logout', function () {
    session()->forget(['user_id', 'user', 'user_role']);
    session()->flush();
    return redirect('/login')->with('message', 'You have been logged out successfully.');
})->name('logout');

// File download route — serves files stored in storage/app/ (local disk)
Route::get('/files/download', function (\Illuminate\Http\Request $request) {
    $path = $request->query('path');
    if (!$path) abort(404);
    // Prevent directory traversal
    $path = str_replace(['..', '\\'], '', $path);
    if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
        abort(404);
    }
    return \Illuminate\Support\Facades\Storage::disk('local')->response($path);
})->middleware('checkauth')->name('file.download');

// Admin Routes
Route::prefix('admin')->middleware('checkauth')->group(function () {
    Route::get('/dashboard', function () {
        $totalUsers = \App\Models\User::where('deleted_at', null)->count();
        $admins = \App\Models\User::where('role', 'admin')->where('deleted_at', null)->count();
        $faculty = \App\Models\User::where('role', 'faculty')->where('deleted_at', null)->count();
        $students = \App\Models\User::where('role', 'student')->where('deleted_at', null)->count();
        
        return view('admin.dashboard', [
            'totalUsers' => $totalUsers,
            'admins' => $admins,
            'faculty' => $faculty,
            'students' => $students,
        ]);
    });
    
    // Sections Routes
    Route::get('/sections', function () {
        $sections = Section::all();
        return view('admin.sections.index', ['sections' => $sections]);
    });
    
    Route::get('/sections/create', function () {
        // Get faculty users who aren't already assigned to a section
        $availableFaculty = \App\Models\User::where('role', 'faculty')
            ->where('deleted_at', null)
            ->whereNotIn('id', \App\Models\Section::where('deleted_at', null)->whereNotNull('faculty_id')->pluck('faculty_id'))
            ->get();
        
        return view('admin.sections.create', [
            'availableFaculty' => $availableFaculty,
            'hasFacultyAlert' => false
        ]);
    });
    
    Route::post('/sections', function (\Illuminate\Http\Request $request) {
        // Validate faculty isn't already assigned to another section
        if ($request->filled('faculty_id')) {
            $facultyId = $request->input('faculty_id');
            
            // Check if faculty is already assigned
            if (\App\Models\Section::isFacultyAssigned($facultyId)) {
                $existingSection = \App\Models\Section::getFacultySection($facultyId);
                return back()->withErrors([
                    'faculty_id' => "This faculty member is already assigned to section '{$existingSection->name}'. Please contact the admin to make changes."
                ])->withInput();
            }
        }

        $section = Section::create([
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
        
        // Sync faculty user's section_id
        if ($request->filled('faculty_id')) {
            $faculty = \App\Models\User::findOrFail($request->input('faculty_id'));
            $faculty->update(['section_id' => $section->id]);
        }
        
        return redirect('/admin/sections')->with('success', 'Section created successfully!');
    });
    
    Route::post('/sections/{id}', function ($id, \Illuminate\Http\Request $request) {
        $section = Section::findOrFail($id);
        $newFacultyId = $request->input('faculty_id');
        $oldFacultyId = $section->faculty_id;
        
        // Check if faculty is being changed and if new faculty is already assigned elsewhere
        if ($newFacultyId && $newFacultyId != $section->faculty_id) {
            if (\App\Models\Section::isFacultyAssigned($newFacultyId, $id)) {
                $existingSection = \App\Models\Section::where('faculty_id', $newFacultyId)
                    ->where('id', '!=', $id)
                    ->where('deleted_at', null)
                    ->first();
                return back()->withErrors([
                    'faculty_id' => "This faculty member is already assigned to section '{$existingSection->name}'. Please contact the admin to make changes."
                ])->withInput();
            }
        }
        
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
        
        // Sync faculty user's section_id
        // If old faculty exists and is being removed, clear their section_id
        if ($oldFacultyId && $oldFacultyId != $newFacultyId) {
            $oldFaculty = \App\Models\User::find($oldFacultyId);
            if ($oldFaculty) {
                $oldFaculty->update(['section_id' => null]);
            }
        }
        
        // If new faculty is assigned, update their section_id
        if ($newFacultyId) {
            $newFaculty = \App\Models\User::findOrFail($newFacultyId);
            $newFaculty->update(['section_id' => $section->id]);
        }
        
        return redirect('/admin/sections')->with('success', 'Section updated successfully!');
    });
    
    Route::get('/sections/{id}/edit', function ($id) {
        $section = Section::findOrFail($id);
        // Get faculty users who aren't assigned to another section (excluding the current section's faculty)
        $availableFaculty = \App\Models\User::where('role', 'faculty')
            ->where('deleted_at', null)
            ->where(function ($query) use ($section) {
                $query->whereNotIn('id', \App\Models\Section::where('deleted_at', null)->where('id', '!=', $section->id)->whereNotNull('faculty_id')->pluck('faculty_id'))
                    ->orWhere('id', $section->faculty_id);
            })
            ->get();
        
        $facultyAssignedSection = null;
        if ($section->faculty_id) {
            $facultyAssignedSection = $section->faculty;
        }
        
        return view('admin.sections.create', [
            'section' => $section, 
            'availableFaculty' => $availableFaculty,
            'facultyAssignedSection' => $facultyAssignedSection
        ]);
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
    
    // User Management Routes
    Route::get('/users', function (\Illuminate\Http\Request $request) {
        $query = \App\Models\User::query();
        
        // Search filters
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                  ->orWhere('last_name', 'like', "%$search%")
                  ->orWhere('username', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
            });
        }
        
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }
        
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('section') && $request->section) {
            $query->where('section_id', $request->section);
        }
        
        $users = $query->paginate(15);
        $sections = \App\Models\Section::all();
        
        return view('admin.users.index', ['users' => $users, 'sections' => $sections]);
    })->name('users.index');
    
    Route::get('/users/create', function () {
        $sections = \App\Models\Section::all();
        return view('admin.users.create', ['sections' => $sections]);
    })->name('users.create');
    
    Route::post('/users', function (\Illuminate\Http\Request $request) {
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,faculty,student',
        ]);
        
        // Check if faculty is being assigned to a section with existing faculty
        if ($request->input('role') === 'faculty' && $request->filled('section_id')) {
            $section = Section::findOrFail($request->input('section_id'));
            if ($section->faculty_id) {
                return back()->withErrors([
                    'section_id' => "Section '{$section->name}' already has a faculty member assigned. Please select a different section or contact the admin to make changes."
                ])->withInput();
            }
        }
        
        $validated['password'] = bcrypt($validated['password']);
        $validated['middle_name'] = $request->input('middle_name');
        $validated['employee_id'] = $request->input('employee_id');
        $validated['student_id'] = $request->input('student_id');
        $validated['contact'] = $request->input('contact');
        $validated['department'] = $request->input('department');
        $validated['section_id'] = $request->input('section_id');
        $validated['status'] = $request->input('status', 'active');
        
        $user = \App\Models\User::create($validated);
        
        // Synchronize section faculty if this is a faculty user
        if ($request->input('role') === 'faculty' && $request->filled('section_id')) {
            $section = Section::find($request->input('section_id'));
            if ($section) {
                $section->update(['faculty_id' => $user->id]);
            }
        }
        
        return redirect('/admin/users')->with('success', 'User created successfully!');
    })->name('users.store');
    
    Route::get('/users/{id}/edit', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        $sections = \App\Models\Section::all();
        return view('admin.users.create', ['user' => $user, 'sections' => $sections]);
    })->name('users.edit');
    
    Route::post('/users/{id}', function ($id, \Illuminate\Http\Request $request) {
        $user = \App\Models\User::findOrFail($id);
        
        $validated = $request->validate([
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
        ]);
        
        // Store old values before updating
        $oldSectionId = $user->section_id;
        $newSectionId = $request->input('section_id');
        $isUserFaculty = $user->role === 'faculty';
        
        // Check if faculty is being assigned to a section
        if ($isUserFaculty) {
            // Only validate if section is changing
            if ($newSectionId && $newSectionId != $oldSectionId) {
                $section = Section::findOrFail($newSectionId);
                if ($section->faculty_id && $section->faculty_id != $user->id) {
                    return back()->withErrors([
                        'section_id' => "Section '{$section->name}' already has a faculty member assigned. Please select a different section or contact the admin to make changes."
                    ])->withInput();
                }
            }
        }
        
        $validated['first_name'] = $request->input('first_name');
        $validated['middle_name'] = $request->input('middle_name');
        $validated['last_name'] = $request->input('last_name');
        $validated['contact'] = $request->input('contact');
        $validated['department'] = $request->input('department');
        $validated['section_id'] = $request->input('section_id');
        $validated['status'] = $request->input('status');
        
        if ($request->input('password')) {
            $validated['password'] = bcrypt($request->input('password'));
        }
        
        $user->update($validated);
        
        // Synchronize section faculty assignments if user is faculty
        if ($isUserFaculty) {
            // If faculty had a section before, remove faculty_id from old section
            if ($oldSectionId && $oldSectionId != $newSectionId) {
                $oldSection = Section::find($oldSectionId);
                if ($oldSection && $oldSection->faculty_id == $user->id) {
                    $oldSection->update(['faculty_id' => null]);
                }
            }
            
            // If faculty is assigned to a new section, set faculty_id in section
            if ($newSectionId) {
                $newSection = Section::find($newSectionId);
                if ($newSection) {
                    // Only set if not already set to this faculty
                    if ($newSection->faculty_id != $user->id) {
                        $newSection->update(['faculty_id' => $user->id]);
                    }
                }
            }
        }
        
        return redirect('/admin/users')->with('success', 'User updated successfully!');
    })->name('users.update');
    
    Route::get('/users/{id}/view', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        return view('admin.users.view', ['user' => $user]);
    })->name('users.view');
    
    Route::delete('/users/{id}', function ($id) {
        $user = \App\Models\User::findOrFail($id);
        
        // If this is a faculty user assigned to a section, clear the section's faculty_id
        if ($user->role === 'faculty' && $user->section_id) {
            $section = Section::find($user->section_id);
            if ($section && $section->faculty_id == $user->id) {
                $section->update(['faculty_id' => null]);
            }
        }
        
        $user->delete();
        return redirect('/admin/users')->with('success', 'User archived!');
    })->name('users.destroy');
    
    Route::get('/users-archive', function () {
        $deletedUsers = \App\Models\User::onlyTrashed()->paginate(15);
        return view('admin.users.archive', ['users' => $deletedUsers]);
    })->name('users.archive');
    
    Route::post('/users/{id}/restore', function ($id) {
        $user = \App\Models\User::withTrashed()->findOrFail($id);
        $user->restore();
        return redirect('/admin/users-archive')->with('success', 'User restored successfully!');
    })->name('users.restore');
    
    Route::get('/users/bulk-import', function () {
        return view('admin.users.bulk-import');
    })->name('users.bulkImportForm');
    
    Route::get('/users/download-sample', function () {
        // Create sample CSV data
        $csvData = "first_name,middle_name,last_name,username,email,password,role,employee_id,student_id,contact,department,section_id,status\n";
        $csvData .= "John,M,Doe,johndoe,john@example.com,password123,faculty,EMP001,,0912345678,IT,1,active\n";
        $csvData .= "Jane,,Smith,janesmith,jane@example.com,password123,student,,STU001,0987654321,IT,1,active\n";
        $csvData .= "Michael,J,Anderson,michaelands,michael@example.com,password123,student,,STU003,0912000000,IT,2,active\n";
        $csvData .= "admin,A,User,adminuser,admin2@example.com,password123,admin,EMP099,,0912111111,Administration,,active\n";
        
        // Return file download response
        return response($csvData)
            ->header('Content-Type', 'text/csv; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="users_sample.csv"')
            ->header('Pragma', 'no-cache')
            ->header('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->header('Expires', '0');
    })->name('users.downloadSample');
    
    Route::post('/users/bulk-import', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt'
        ]);
        
        $file = $request->file('file');
        $handle = fopen($file->getPathname(), 'r');
        
        if (!$handle) {
            return back()->withErrors(['file' => 'Unable to read the file. Please ensure it is a valid CSV file.']);
        }
        
        $header = fgetcsv($handle);
        $imported = 0;
        $errors = [];
        $rowNumber = 2; // Start from 2 since row 1 is headers
        
        while ($row = fgetcsv($handle)) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $rowNumber++;
                    continue;
                }
                
                // Combine header with row data
                $data = array_combine($header, $row);
                
                if (!$data) {
                    $errors[] = "Row {$rowNumber}: Column count mismatch with headers.";
                    $rowNumber++;
                    continue;
                }
                
                // Validate required fields
                $required = ['first_name', 'last_name', 'username', 'email', 'password', 'role'];
                foreach ($required as $field) {
                    if (empty($data[$field])) {
                        $errors[] = "Row {$rowNumber}: Missing required field '{$field}'";
                        $rowNumber++;
                        continue 2;
                    }
                }
                
                // Validate role
                if (!in_array($data['role'], ['admin', 'faculty', 'student'])) {
                    $errors[] = "Row {$rowNumber}: Invalid role '{$data['role']}'. Must be admin, faculty, or student.";
                    $rowNumber++;
                    continue;
                }
                
                // Validate status
                if (!empty($data['status']) && !in_array($data['status'], ['active', 'inactive'])) {
                    $errors[] = "Row {$rowNumber}: Invalid status '{$data['status']}'. Must be active or inactive.";
                    $rowNumber++;
                    continue;
                }
                
                // Set default status if not provided
                if (empty($data['status'])) {
                    $data['status'] = 'active';
                }
                
                // Hash the password
                $data['password'] = bcrypt($data['password']);
                
                // Remove empty values to avoid column conflicts
                $data = array_filter($data, function($value) {
                    return $value !== '';
                });
                
                // Create the user
                $user = \App\Models\User::create($data);
                
                // Synchronize section faculty if this is a faculty user
                if ($data['role'] === 'faculty' && !empty($data['section_id'])) {
                    $section = Section::find($data['section_id']);
                    if ($section) {
                        $section->update(['faculty_id' => $user->id]);
                    }
                }
                
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$rowNumber}: " . $e->getMessage();
            }
            
            $rowNumber++;
        }
        
        fclose($handle);
        
        if ($imported > 0) {
            $message = "Successfully imported {$imported} user(s)";
            if (count($errors) > 0) {
                $message .= " with " . count($errors) . " error(s)";
            }
            
            if (count($errors) > 0) {
                return back()->with('success', $message)->with('import_errors', $errors);
            }
            
            return redirect('/admin/users')->with('success', $message);
        } else {
            return back()->withErrors(['file' => 'No users imported. ' . (count($errors) > 0 ? 'Errors: ' . implode(', ', $errors) : '')]);
        }
    })->name('users.bulkImport');
    
    // Faculty Assignment Management - Find and fix duplicate faculty in sections
    Route::get('/faculty-assignment-audit', function () {
        // Find all sections with multiple faculty members
        $sectionsWithMultipleFaculty = \App\Models\Section::where('deleted_at', null)
            ->with('faculty')
            ->get()
            ->filter(function($section) {
                // Count faculty users assigned to this section
                $facultyCount = \App\Models\User::where('role', 'faculty')
                    ->where('section_id', $section->id)
                    ->where('deleted_at', null)
                    ->count();
                return $facultyCount > 1;
            });
        
        $duplicateData = [];
        foreach ($sectionsWithMultipleFaculty as $section) {
            $faculty = \App\Models\User::where('role', 'faculty')
                ->where('section_id', $section->id)
                ->where('deleted_at', null)
                ->get();
            
            $duplicateData[] = [
                'section' => $section,
                'faculty' => $faculty,
                'count' => $faculty->count()
            ];
        }
        
        return view('admin.faculty-assignment-audit', ['duplicates' => $duplicateData]);
    })->name('faculty.audit');
    
    // Auto-fix: Keep only the first faculty, remove others from section
    Route::post('/faculty-assignment-audit/fix/{sectionId}', function ($sectionId) {
        $section = Section::findOrFail($sectionId);
        
        // Get all faculty in this section ordered by their assignment time (created_at)
        $faculty = \App\Models\User::where('role', 'faculty')
            ->where('section_id', $section->id)
            ->where('deleted_at', null)
            ->orderBy('created_at', 'asc')
            ->get();
        
        if ($faculty->count() <= 1) {
            return back()->with('info', 'No duplicates found for this section.');
        }
        
        // Keep the first one and remove section assignment from the rest
        $keepFaculty = $faculty->first();
        $removeFaculty = $faculty->skip(1);
        
        foreach ($removeFaculty as $faculty_user) {
            $faculty_user->update(['section_id' => null]);
        }
        
        // Make sure section only has one faculty_id
        $section->update(['faculty_id' => $keepFaculty->id]);
        
        return back()->with('success', "Fixed duplicate faculty assignment. Kept {$keepFaculty->first_name} {$keepFaculty->last_name}, removed others from section.");
    })->name('faculty.fixDuplicate');
    
    // Manual fix: Select which faculty to keep
    Route::post('/faculty-assignment-audit/fix-manual/{sectionId}', function ($sectionId, \Illuminate\Http\Request $request) {
        $section = Section::findOrFail($sectionId);
        $keepFacultyId = $request->input('keep_faculty_id');
        
        $keepFaculty = \App\Models\User::findOrFail($keepFacultyId);
        
        if ($keepFaculty->role !== 'faculty' || $keepFaculty->section_id !== $section->id) {
            return back()->withErrors(['error' => 'Invalid faculty selection.']);
        }
        
        // Remove section assignment from all other faculty in this section
        \App\Models\User::where('role', 'faculty')
            ->where('section_id', $section->id)
            ->where('id', '!=', $keepFacultyId)
            ->where('deleted_at', null)
            ->update(['section_id' => null]);
        
        // Ensure section faculty_id is set to the kept faculty
        $section->update(['faculty_id' => $keepFacultyId]);
        
        return back()->with('success', "Section assignment fixed. {$keepFaculty->first_name} {$keepFaculty->last_name} is now the only faculty for this section.");
    })->name('faculty.fixDuplicateManual');
    
    Route::get('/cms', function () {
        $cms = \App\Models\CMS::all()->keyBy('key');
        return view('admin.cms', ['cms' => $cms]);
    });
    
    Route::post('/cms', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'header' => 'required|string|max:255',
            'subheader' => 'required|string|max:500',
            'about' => 'required|string',
            'mission' => 'required|string',
            'vision' => 'required|string',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string|max:20',
            'contact_address' => 'required|string|max:255',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'linkedin_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'youtube_url' => 'nullable|url',
        ]);

        $cmsFields = [
            'header' => $request->header,
            'subheader' => $request->subheader,
            'about' => $request->about,
            'mission' => $request->mission,
            'vision' => $request->vision,
            'contact_email' => $request->contact_email,
            'contact_phone' => $request->contact_phone,
            'contact_address' => $request->contact_address,
            'facebook_url' => $request->facebook_url ?? 'https://facebook.com',
            'instagram_url' => $request->instagram_url ?? 'https://instagram.com',
            'linkedin_url' => $request->linkedin_url ?? 'https://linkedin.com',
            'twitter_url' => $request->twitter_url ?? 'https://twitter.com',
            'youtube_url' => $request->youtube_url ?? 'https://youtube.com',
        ];

        foreach ($cmsFields as $key => $value) {
            $section = in_array($key, ['facebook_url', 'instagram_url', 'linkedin_url', 'twitter_url', 'youtube_url']) ? 'social_media' : 'general';
            \App\Models\CMS::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'section' => $section]
            );
        }

        return back()->with('success', 'CMS Settings updated successfully!');
    })->name('cms.update');
    
    // Announcements Routes
    Route::get('/announcements', function () {
        $activeAnnouncements = \App\Models\Announcement::active()->latest()->get();
        $archivedAnnouncements = \App\Models\Announcement::archived()->latest()->get();
        return view('admin.announcements.index', [
            'activeAnnouncements' => $activeAnnouncements,
            'archivedAnnouncements' => $archivedAnnouncements
        ]);
    });
    
    Route::get('/announcements/create', function () {
        return view('admin.announcements.create');
    });
    
    Route::post('/announcements', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        \App\Models\Announcement::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => 'active',
            'created_by' => session('user_id'),
        ]);

        return redirect('/admin/announcements')->with('success', 'Announcement published successfully!');
    });
    
    Route::get('/announcements/{id}/edit', function ($id) {
        $announcement = \App\Models\Announcement::find($id);
        if (!$announcement) {
            return redirect('/admin/announcements')->with('error', 'Announcement not found!');
        }
        return view('admin.announcements.edit', ['announcement' => $announcement]);
    });
    
    Route::put('/announcements/{id}', function (\Illuminate\Http\Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $announcement = \App\Models\Announcement::find($id);
        if (!$announcement) {
            return redirect('/admin/announcements')->with('error', 'Announcement not found!');
        }

        $announcement->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'status' => $request->input('status'),
        ]);

        return redirect('/admin/announcements')->with('success', 'Announcement updated successfully!');
    });
    
    Route::post('/announcements/{id}/archive', function ($id) {
        $announcement = \App\Models\Announcement::find($id);
        if (!$announcement) {
            return redirect('/admin/announcements')->with('error', 'Announcement not found!');
        }

        $announcement->delete();
        return redirect('/admin/announcements')->with('success', 'Announcement archived successfully!');
    });
    
    Route::post('/announcements/{id}/restore', function ($id) {
        $announcement = \App\Models\Announcement::withTrashed()->find($id);
        if (!$announcement) {
            return redirect('/admin/announcements')->with('error', 'Announcement not found!');
        }

        $announcement->restore();
        return redirect('/admin/announcements')->with('success', 'Announcement restored successfully!');
    });
    
    Route::post('/announcements/{id}/delete', function ($id) {
        $announcement = \App\Models\Announcement::withTrashed()->find($id);
        if (!$announcement) {
            return redirect('/admin/announcements')->with('error', 'Announcement not found!');
        }

        $announcement->forceDelete();
        return redirect('/admin/announcements')->with('success', 'Announcement permanently deleted!');
    });
    
    // Reports & Export Routes
    Route::get('/reports', function () {
        return view('admin.reports', [
            'usersCount' => \App\Models\User::count(),
            'sectionsCount' => \App\Models\Section::count(),
            'announcementsCount' => \App\Models\Announcement::count(),
            'cmsCount' => \App\Models\CMS::count(),
            'activeUsersCount' => \App\Models\User::where('status', 'active')->count(),
            'activeSectionsCount' => \App\Models\Section::where('status', 'active')->count(),
            'activeAnnouncementsCount' => \App\Models\Announcement::where('status', 'active')->count(),
        ]);
    });
    
    // Export individual tables as CSV
    Route::get('/reports/export/{table}', function ($table) {
        $allowedTables = ['users', 'sections', 'announcements', 'cms_settings'];
        
        if (!in_array($table, $allowedTables)) {
            return response()->json(['error' => 'Table not found'], 404);
        }

        $data = \App\Helpers\CsvExporter::exportTable($table);
        
        if (!$data) {
            return response()->json(['error' => 'No data found'], 404);
        }

        $filename = $data['filename'];
        $headers = $data['headers'];
        $rows = $data['rows'];

        // Determine the actual table name for mapping
        $tableMap = [
            'cms_settings' => 'CMS Settings',
            'users' => 'Users',
            'sections' => 'Sections',
            'announcements' => 'Announcements',
        ];

        return response()->streamDownload(function() use ($headers, $rows) {
            \App\Helpers\CsvExporter::generateCsv($headers, $rows);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    });

    // Preview data endpoint
    Route::get('/reports/preview/{table}', function ($table) {
        $allowedTables = [
            'users' => 'users',
            'sections' => 'sections',
            'announcements' => 'announcements',
            'cms' => 'cms_settings',
        ];
        
        if (!isset($allowedTables[$table])) {
            return response()->json(['success' => false, 'error' => 'Table not found']);
        }

        $dbTable = $allowedTables[$table];
        $data = \App\Helpers\CsvExporter::exportTable($dbTable);
        
        if (!$data) {
            return response()->json(['success' => false, 'error' => 'No data found']);
        }

        $html = \App\Helpers\CsvExporter::generatePreviewHtml($data['headers'], $data['rows'], 5);
        
        return response()->json(['success' => true, 'html' => $html]);
    });

    // Export all tables as ZIP
    Route::get('/reports/export/all', function () {
        $zipPath = storage_path('app/exports/complete_db_' . now()->format('Y-m-d_H-i-s') . '.zip');
        
        // Create exports directory if it doesn't exist
        if (!file_exists(dirname($zipPath))) {
            mkdir(dirname($zipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== true) {
            abort(500, 'Could not create ZIP file');
        }

        $tables = ['users', 'sections', 'announcements', 'cms_settings'];

        foreach ($tables as $table) {
            $data = \App\Helpers\CsvExporter::exportTable($table);
            
            if ($data) {
                $filename = str_replace(['_' . now()->format('Y-m-d_H-i-s') . '.csv'], '', $data['filename']);
                
                // Create CSV content in memory
                $csvOutput = fopen('php://memory', 'r+');
                fwrite($csvOutput, "\xEF\xBB\xBF"); // BOM
                fputcsv($csvOutput, $data['headers']);
                
                foreach ($data['rows'] as $row) {
                    fputcsv($csvOutput, $row);
                }
                
                rewind($csvOutput);
                $csvContent = stream_get_contents($csvOutput);
                fclose($csvOutput);
                
                $zip->addFromString($filename . '.csv', $csvContent);
            }
        }

        $zip->close();

        return response()->download($zipPath)->deleteFileAfterSend(true);
    });
});

// Faculty Routes
Route::prefix('faculty')->middleware('checkauth')->group(function () {
    Route::get('/dashboard', function () {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        
        $section = \App\Models\Section::find($user->section_id);
        $studentsCount = $section ? $section->students()->count() : 0;
        
        return view('faculty.dashboard', [
            'user' => $user,
            'section' => $section,
            'studentsCount' => $studentsCount,
        ]);
    });

    Route::get('/section', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $query = \App\Models\Section::where('faculty_id', $user->id)->where('deleted_at', null);

        if ($request->filled('school_year')) {
            $query->where('school_year', 'like', '%'.$request->input('school_year').'%');
        }

        if ($request->filled('term')) {
            $query->where('term', 'like', '%'.$request->input('term').'%');
        }

        $sections = $query->orderBy('school_year', 'desc')->orderBy('term', 'asc')->get();

        return view('faculty.section', [
            'user' => $user,
            'sections' => $sections,
            'filters' => [
                'school_year' => $request->input('school_year', ''),
                'term' => $request->input('term', ''),
            ],
        ]);
    });

    Route::get('/section/{id}/students', function ($id) {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::where('id', $id)
            ->where('faculty_id', $user->id)
            ->where('deleted_at', null)
            ->first();

        if (!$section) {
            return redirect('/faculty/section')->with('error', 'Section not found or not assigned to you.');
        }

        $students = $section->students()->get();

        return view('faculty.section_students', [
            'user' => $user,
            'section' => $section,
            'students' => $students,
        ]);
    });

    Route::get('/section/{sectionId}/students/{studentId}', function ($sectionId, $studentId) {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::where('id', $sectionId)
            ->where('faculty_id', $user->id)
            ->where('deleted_at', null)
            ->first();

        if (!$section) {
            return redirect('/faculty/section')->with('error', 'Section not found or not assigned to you.');
        }

        $student = \App\Models\User::where('id', $studentId)
            ->where('section_id', $sectionId)
            ->where('role', 'student')
            ->where('deleted_at', null)
            ->first();

        if (!$student) {
            return redirect('/faculty/section/'.$sectionId.'/students')->with('error', 'Student not found in this section.');
        }

        $checklistItems = [
            'Registration card',
            'Medical Record',
            'Receipt of OJT Kit',
            'Waiver',
            'Endorsement letter',
            'MOA',
            'DTR',
            'Weekly report',
            'Monthly appraisal',
            'Supervisor evaluation',
            'Certificate of completion',
        ];

        // Fetch latest entry per item for real status display
        $allEntries = \App\Models\StudentChecklist::where('student_id', $studentId)
            ->whereIn('item', $checklistItems)
            ->orderBy('created_at', 'desc')
            ->get();

        $entriesByItem = [];
        foreach ($checklistItems as $item) {
            $entriesByItem[$item] = $allEntries->where('item', $item)->first();
        }

        return view('faculty.student_checklist', [
            'user' => $user,
            'section' => $section,
            'student' => $student,
            'checklistItems' => $checklistItems,
            'entriesByItem' => $entriesByItem,
        ]);
    });

    Route::get('/section/{sectionId}/students/{studentId}/checklist/{item}', function ($sectionId, $studentId, $item) {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::where('id', $sectionId)
            ->where('faculty_id', $user->id)
            ->where('deleted_at', null)
            ->first();

        if (!$section) {
            return redirect('/faculty/section')->with('error', 'Section not found or not assigned to you.');
        }

        $student = \App\Models\User::where('id', $studentId)
            ->where('section_id', $sectionId)
            ->where('role', 'student')
            ->where('deleted_at', null)
            ->first();

        if (!$student) {
            return redirect('/faculty/section/'.$sectionId.'/students')->with('error', 'Student not found in this section.');
        }

        $checklistItem = urldecode($item);
        $recurringItems = ['DTR', 'Weekly report', 'Monthly appraisal', 'Supervisor evaluation', 'Certificate of completion'];

        if (in_array($checklistItem, $recurringItems)) {
            $entries = \App\Models\StudentChecklist::where('section_id', $sectionId)
                ->where('student_id', $studentId)
                ->where('item', $checklistItem)
                ->orderBy('created_at', 'desc')
                ->get();
            $entry = $entries->first();
        } else {
            $entry = \App\Models\StudentChecklist::firstOrCreate(
                ['section_id' => $sectionId, 'student_id' => $studentId, 'item' => $checklistItem],
                ['student_submitted_at' => now(), 'student_encoded_at' => now(), 'faculty_status' => 'pending']
            );
            $entries = collect([$entry]);
        }

        return view('faculty.student_checklist_item', [
            'user'    => $user,
            'section' => $section,
            'student' => $student,
            'item'    => $checklistItem,
            'entry'   => $entry,
            'entries' => $entries,
        ]);
    });

    // Review a specific recurring entry (DTR, Weekly report, etc.) by entry ID
    Route::post('/section/{sectionId}/students/{studentId}/checklist/{item}/{entryId}', function (\Illuminate\Http\Request $request, $sectionId, $studentId, $item, $entryId) {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');

        $checklistItem = urldecode($item);

        $entry = \App\Models\StudentChecklist::where('id', $entryId)
            ->where('section_id', $sectionId)
            ->where('student_id', $studentId)
            ->where('item', $checklistItem)
            ->firstOrFail();

        $validated = $request->validate([
            'faculty_status'           => 'required|in:pending,approved,declined',
            'faculty_remarks'          => 'nullable|string',
            'faculty_dtr_target_hours' => 'nullable|numeric|min:1',
            'faculty_weekly_remarks'   => 'nullable|string',
            'faculty_appraisal_remarks'        => 'nullable|string',
            'faculty_supervisor_eval_remarks'  => 'nullable|string',
            'faculty_coc_remarks'             => 'nullable|string',
        ]);

        $remarks = $validated['faculty_remarks'] ?? $validated['faculty_weekly_remarks'] ?? $validated['faculty_appraisal_remarks'] ?? $validated['faculty_supervisor_eval_remarks'] ?? $validated['faculty_coc_remarks'] ?? null;

        if ($validated['faculty_status'] === 'declined' && empty($remarks)) {
            return back()->withErrors(['faculty_remarks' => 'Please provide a reason when declining.'])->withInput();
        }

        $updateData = [
            'faculty_status'   => $validated['faculty_status'],
            'faculty_remarks'  => $remarks,
            'faculty_reviewed_at' => now(),
        ];

        if ($checklistItem === 'DTR' && $request->filled('faculty_dtr_target_hours')) {
            $updateData['faculty_dtr_target_hours'] = $validated['faculty_dtr_target_hours'];
            $updateData['faculty_dtr_reviewed_at']  = now();
        }
        if ($checklistItem === 'Weekly report' && $request->filled('faculty_weekly_remarks')) {
            $updateData['faculty_weekly_remarks']      = $validated['faculty_weekly_remarks'];
            $updateData['faculty_weekly_reviewed_at']  = now();
        }
        if ($checklistItem === 'Monthly appraisal' && $request->filled('faculty_appraisal_remarks')) {
            $updateData['faculty_appraisal_remarks']     = $validated['faculty_appraisal_remarks'];
            $updateData['faculty_appraisal_reviewed_at'] = now();
        }
        if ($checklistItem === 'Supervisor evaluation' && $request->filled('faculty_supervisor_eval_remarks')) {
            $updateData['faculty_supervisor_eval_remarks']     = $validated['faculty_supervisor_eval_remarks'];
            $updateData['faculty_supervisor_eval_reviewed_at'] = now();
        }
        if ($checklistItem === 'Certificate of completion' && $request->filled('faculty_coc_remarks')) {
            $updateData['faculty_coc_remarks']     = $validated['faculty_coc_remarks'];
            $updateData['faculty_coc_reviewed_at'] = now();
        }

        $entry->update($updateData);

        return redirect("/faculty/section/{$sectionId}/students/{$studentId}/checklist/".urlencode($item))
            ->with('success', 'Entry reviewed successfully.');
    });

    Route::post('/section/{sectionId}/students/{studentId}/checklist/{item}', function (\Illuminate\Http\Request $request, $sectionId, $studentId, $item) {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::where('id', $sectionId)
            ->where('faculty_id', $user->id)
            ->where('deleted_at', null)
            ->first();

        if (!$section) {
            return redirect('/faculty/section')->with('error', 'Section not found or not assigned to you.');
        }

        $student = \App\Models\User::where('id', $studentId)
            ->where('section_id', $sectionId)
            ->where('role', 'student')
            ->where('deleted_at', null)
            ->first();

        if (!$student) {
            return redirect('/faculty/section/'.$sectionId.'/students')->with('error', 'Student not found in this section.');
        }

        $checklistItem = urldecode($item);

        $validated = $request->validate([
            'faculty_status' => 'required|in:pending,approved,declined',
            'faculty_remarks' => 'nullable|string',
            'faculty_dtr_target_hours' => 'nullable|numeric|min:1',
            'faculty_weekly_remarks' => 'nullable|string',
            'faculty_appraisal_remarks' => 'nullable|string',
            'faculty_supervisor_eval_remarks' => 'nullable|string',
            'faculty_coc_remarks' => 'nullable|string',
        ]);

        if ($validated['faculty_status'] === 'declined' && empty($validated['faculty_remarks']) && empty($validated['faculty_weekly_remarks']) && empty($validated['faculty_appraisal_remarks']) && empty($validated['faculty_supervisor_eval_remarks']) && empty($validated['faculty_coc_remarks'])) {
            return back()->withErrors(['faculty_remarks' => 'Please set a reason when declining'])->withInput();
        }

        $updateData = [
            'faculty_status' => $validated['faculty_status'],
            'faculty_remarks' => $validated['faculty_remarks'] ?? $validated['faculty_weekly_remarks'] ?? $validated['faculty_appraisal_remarks'] ?? $validated['faculty_supervisor_eval_remarks'] ?? $validated['faculty_coc_remarks'] ?? null,
            'faculty_reviewed_at' => now(),
        ];

        // Handle DTR-specific field
        if ($checklistItem === 'DTR' && $request->filled('faculty_dtr_target_hours')) {
            $updateData['faculty_dtr_target_hours'] = $validated['faculty_dtr_target_hours'];
            $updateData['faculty_dtr_reviewed_at'] = now();
        }

        // Handle Weekly Report-specific field
        if ($checklistItem === 'Weekly report' && $request->filled('faculty_weekly_remarks')) {
            $updateData['faculty_weekly_remarks'] = $validated['faculty_weekly_remarks'];
            $updateData['faculty_weekly_reviewed_at'] = now();
        }

        // Handle Monthly Appraisal-specific field
        if ($checklistItem === 'Monthly appraisal' && $request->filled('faculty_appraisal_remarks')) {
            $updateData['faculty_appraisal_remarks'] = $validated['faculty_appraisal_remarks'];
            $updateData['faculty_appraisal_reviewed_at'] = now();
        }

        // Handle Supervisor Evaluation-specific field
        if ($checklistItem === 'Supervisor evaluation' && $request->filled('faculty_supervisor_eval_remarks')) {
            $updateData['faculty_supervisor_eval_remarks'] = $validated['faculty_supervisor_eval_remarks'];
            $updateData['faculty_supervisor_eval_reviewed_at'] = now();
        }

        // Handle Certificate of Completion-specific field
        if ($checklistItem === 'Certificate of completion' && $request->filled('faculty_coc_remarks')) {
            $updateData['faculty_coc_remarks'] = $validated['faculty_coc_remarks'];
            $updateData['faculty_coc_reviewed_at'] = now();
        }

        $entry = \App\Models\StudentChecklist::updateOrCreate(
            [
                'section_id' => $sectionId,
                'student_id' => $studentId,
                'item' => $checklistItem,
            ],
            $updateData
        );

        // Enforce submitted status for special items once faculty sees them
        if (in_array($checklistItem, ['Waiver', 'Endorsement letter']) && $entry->student_submission_status === 'pending') {
            $entry->student_submission_status = 'submitted';
            $entry->save();
        }

        return redirect("/faculty/section/{$sectionId}/students/{$studentId}/checklist/".urlencode($item))
            ->with('success', 'Checklist item updated successfully.');
    });

    Route::get('/incident-reports', function () {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        return view('faculty.incident_reports', ['user' => $user]);
    });

    Route::get('/reports', function () {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        return view('faculty.reports', ['user' => $user]);
    });

    Route::get('/faqs', function () {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        return view('faculty.faqs', ['user' => $user]);
    });

    Route::get('/announcements', function () {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');
        $active   = \App\Models\Announcement::active()->with('creator')->latest()->get();
        $archived = \App\Models\Announcement::withTrashed()->where('status', 'archived')->with('creator')->latest()->get();
        return view('faculty.announcements', compact('user', 'active', 'archived'));
    });

    Route::post('/announcements', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        \App\Models\Announcement::create([
            'title'      => $request->input('title'),
            'content'    => $request->input('content'),
            'status'     => 'active',
            'created_by' => $user->id,
        ]);
        return redirect('/faculty/announcements')->with('success', 'Announcement published successfully.');
    });

    Route::put('/announcements/{id}', function (\Illuminate\Http\Request $request, $id) {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');
        $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);
        $announcement = \App\Models\Announcement::findOrFail($id);
        $announcement->update(['title' => $request->input('title'), 'content' => $request->input('content')]);
        return redirect('/faculty/announcements')->with('success', 'Announcement updated.');
    });

    Route::post('/announcements/{id}/archive', function ($id) {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');
        $announcement = \App\Models\Announcement::findOrFail($id);
        $announcement->update(['status' => 'archived']);
        $announcement->delete(); // soft delete
        return redirect('/faculty/announcements')->with('success', 'Announcement archived.');
    });

    Route::post('/announcements/{id}/restore', function ($id) {
        $user = session('user');
        if ($user->role !== 'faculty') return redirect('/admin/dashboard');
        $announcement = \App\Models\Announcement::withTrashed()->findOrFail($id);
        $announcement->restore();
        $announcement->update(['status' => 'active']);
        return redirect('/faculty/announcements')->with('success', 'Announcement restored.');
    });

    Route::get('/profile', function () {
        $user = session('user');
        if ($user->role !== 'faculty') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::find($user->section_id);

        return view('faculty.profile', [
            'user' => $user,
            'section' => $section,
        ]);
    });
});

// Student Routes
Route::prefix('student')->middleware('checkauth')->group(function () {
    Route::get('/dashboard', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        
        $section = \App\Models\Section::find($user->section_id);
        $faculty = $section ? $section->faculty : null;
        
        return view('student.dashboard', [
            'user' => $user,
            'section' => $section,
            'faculty' => $faculty,
        ]);
    });

    // DTR (Daily Time Record) Routes for Students
    Route::get('/dtr', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $dtrEntries = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'DTR')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.dtr', [
            'user' => $user,
            'dtrEntries' => $dtrEntries,
        ]);
    });

    Route::get('/dtr/create', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::find($user->section_id);

        return view('student.dtr_form', [
            'user' => $user,
            'section' => $section,
        ]);
    });

    Route::post('/dtr', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        // Receipt of OJT Kit must be submitted before DTR
        if (!\App\Models\StudentChecklist::where('student_id', $user->id)->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists()) {
            return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock DTR.');
        }

        $validated = $request->validate([
            'student_dtr_week'         => 'required|string',
            'student_dtr_hours'        => 'required|numeric|min:0|max:60',
            'student_dtr_validated_by' => 'required|string|min:3',
            'student_remarks'          => 'nullable|string',
            'student_files'            => 'required|array|min:1',
            'student_files.*'          => 'file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) {
            return back()->withErrors(['error' => 'No section assigned.'])->withInput();
        }

        // Upload DTR files
        $filePaths = [];
        foreach ($request->file('student_files') as $file) {
            if ($file->isValid()) {
                $filePaths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/dtr', $file);
            }
        }

        // Get or calculate total hours
        $totalHours = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'DTR')
            ->where('faculty_status', 'approved')
            ->sum('student_dtr_hours') + $validated['student_dtr_hours'];

        $dtrEntry = \App\Models\StudentChecklist::create([
            'section_id'               => $section->id,
            'student_id'               => $user->id,
            'item'                     => 'DTR',
            'student_dtr_week'         => $validated['student_dtr_week'],
            'student_dtr_hours'        => $validated['student_dtr_hours'],
            'student_dtr_validated_by' => $validated['student_dtr_validated_by'],
            'student_dtr_total_hours'  => $totalHours,
            'student_remarks'          => $validated['student_remarks'],
            'student_files'            => $filePaths,
            'student_submitted_at'     => now(),
            'student_encoded_at'       => now(),
            'faculty_status'           => 'pending',
            'faculty_dtr_target_hours' => 720,
        ]);

        return redirect('/student/dtr')->with('success', 'DTR submitted successfully. Pending faculty review.');
    });

    Route::get('/dtr/{id}/edit', function ($id) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        $entry = \App\Models\StudentChecklist::where('id', $id)->where('student_id', $user->id)->where('item', 'DTR')->firstOrFail();
        if ($entry->faculty_status === 'approved') {
            return redirect('/student/dtr')->with('error', 'Approved DTR entries cannot be edited.');
        }
        return view('student.dtr_form', ['user' => $user, 'entry' => $entry]);
    });

    Route::put('/dtr/{id}', function (\Illuminate\Http\Request $request, $id) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        $entry = \App\Models\StudentChecklist::where('id', $id)->where('student_id', $user->id)->where('item', 'DTR')->firstOrFail();
        if ($entry->faculty_status === 'approved') {
            return redirect('/student/dtr')->with('error', 'Approved DTR entries cannot be edited.');
        }

        $validated = $request->validate([
            'student_dtr_week'         => 'required|string',
            'student_dtr_hours'        => 'required|numeric|min:0|max:60',
            'student_dtr_validated_by' => 'required|string|min:3',
            'student_remarks'          => 'nullable|string',
            'student_files'            => 'nullable|array',
            'student_files.*'          => 'file|max:5120|mimes:pdf,doc,docx,jpg,jpeg,png',
        ]);

        // Upload new files if provided, otherwise keep existing
        $filePaths = $entry->student_files ?? [];
        if ($request->hasFile('student_files')) {
            $filePaths = [];
            foreach ($request->file('student_files') as $file) {
                if ($file->isValid()) {
                    $filePaths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/dtr', $file);
                }
            }
        }

        // Recalculate total: approved others + this entry's new hours
        $approvedOtherHours = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'DTR')->where('faculty_status', 'approved')
            ->where('id', '!=', $id)->sum('student_dtr_hours');
        $totalHours = $approvedOtherHours + $validated['student_dtr_hours'];

        $entry->update([
            'student_dtr_week'         => $validated['student_dtr_week'],
            'student_dtr_hours'        => $validated['student_dtr_hours'],
            'student_dtr_validated_by' => $validated['student_dtr_validated_by'],
            'student_dtr_total_hours'  => $totalHours,
            'student_remarks'          => $validated['student_remarks'],
            'student_files'            => $filePaths,
            'student_submitted_at'     => now(),
            'faculty_status'           => 'pending',
            'faculty_remarks'          => null,
        ]);

        return redirect('/student/dtr')->with('success', 'DTR updated successfully. Pending faculty review.');
    });

    // Weekly Report Routes for Students
    Route::get('/weekly-report', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $weeklyReports = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'Weekly report')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.weekly_report', [
            'user' => $user,
            'weeklyReports' => $weeklyReports,
        ]);
    });

    Route::get('/weekly-report/create', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::find($user->section_id);

        return view('student.weekly_report_form', [
            'user' => $user,
            'section' => $section,
        ]);
    });

    Route::post('/weekly-report', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        // Receipt of OJT Kit must be submitted before Weekly Report
        if (!\App\Models\StudentChecklist::where('student_id', $user->id)->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists()) {
            return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock Weekly Report.');
        }

        $validated = $request->validate([
            'student_weekly_week' => 'required|string',
            'student_weekly_task_description' => 'required|string|min:10',
            'student_weekly_supervisor_feedback' => 'required|string|min:5',
            'student_weekly_files.*' => 'nullable|file|max:5120',
        ]);

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) {
            return back()->withErrors(['error' => 'No section assigned.'])->withInput();
        }

        $uploadedFiles = [];
        if ($request->hasFile('student_weekly_files')) {
            foreach ($request->file('student_weekly_files') as $file) {
                if ($file->isValid()) {
                    $path = \Illuminate\Support\Facades\Storage::putFile('weekly-reports', $file);
                    $uploadedFiles[] = $path;
                }
            }
        }

        $weeklyReportEntry = \App\Models\StudentChecklist::create([
            'section_id' => $section->id,
            'student_id' => $user->id,
            'item' => 'Weekly report',
            'student_weekly_week' => $validated['student_weekly_week'],
            'student_weekly_task_description' => $validated['student_weekly_task_description'],
            'student_weekly_supervisor_feedback' => $validated['student_weekly_supervisor_feedback'],
            'student_weekly_files' => $uploadedFiles,
            'student_weekly_submitted_at' => now(),
            'faculty_status' => 'pending',
        ]);

        return redirect('/student/weekly-report')->with('success', 'Weekly report submitted successfully. Pending faculty review.');
    });

    // Monthly Appraisal Routes for Students
    Route::get('/monthly-appraisal', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $appraisals = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'Monthly appraisal')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.monthly_appraisal', [
            'user' => $user,
            'appraisals' => $appraisals,
        ]);
    });

    Route::get('/monthly-appraisal/create', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        $section = \App\Models\Section::find($user->section_id);

        return view('student.monthly_appraisal_form', [
            'user' => $user,
            'section' => $section,
        ]);
    });

    Route::post('/monthly-appraisal', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        // Receipt of OJT Kit must be submitted before Monthly Appraisal
        if (!\App\Models\StudentChecklist::where('student_id', $user->id)->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists()) {
            return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock Monthly Appraisal.');
        }

        $validated = $request->validate([
            'student_appraisal_month' => 'required|string',
            'student_appraisal_file' => 'nullable|file|max:5120',
            'student_appraisal_feedback' => 'nullable|string',
            'student_appraisal_grade_rating' => 'nullable|string',
            'student_appraisal_evaluated_by' => 'nullable|string',
        ]);

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) {
            return back()->withErrors(['error' => 'No section assigned.'])->withInput();
        }

        $uploadedFile = null;
        if ($request->hasFile('student_appraisal_file')) {
            if ($request->file('student_appraisal_file')->isValid()) {
                $uploadedFile = \Illuminate\Support\Facades\Storage::putFile('monthly-appraisals', $request->file('student_appraisal_file'));
            }
        }

        $appraisalEntry = \App\Models\StudentChecklist::create([
            'section_id' => $section->id,
            'student_id' => $user->id,
            'item' => 'Monthly appraisal',
            'student_appraisal_month' => $validated['student_appraisal_month'],
            'student_appraisal_file' => $uploadedFile,
            'student_appraisal_feedback' => $validated['student_appraisal_feedback'],
            'student_appraisal_grade_rating' => $validated['student_appraisal_grade_rating'],
            'student_appraisal_evaluated_by' => $validated['student_appraisal_evaluated_by'],
            'student_appraisal_submitted_at' => now(),
            'faculty_status' => 'pending',
        ]);

        return redirect('/student/monthly-appraisal')->with('success', 'Monthly appraisal submitted successfully. Pending faculty review.');
    });

    Route::get('/supervisor-eval', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        $section = \App\Models\Section::find($user->section_id);
        $entries = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'Supervisor evaluation')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('student.supervisor_eval', ['user' => $user, 'section' => $section, 'entries' => $entries]);
    });

    Route::get('/supervisor-eval/create', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        $section = \App\Models\Section::find($user->section_id);
        return view('student.supervisor_eval_form', ['user' => $user, 'section' => $section]);
    });

    Route::post('/supervisor-eval', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        // Receipt of OJT Kit must be submitted before Supervisor Evaluation
        if (!\App\Models\StudentChecklist::where('student_id', $user->id)->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists()) {
            return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock Supervisor Evaluation.');
        }

        $validated = $request->validate([
            'student_supervisor_eval_file' => 'required|file|max:5120',
            'student_supervisor_eval_grade' => 'required|string',
        ]);

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) {
            return back()->withErrors(['error' => 'No section assigned.'])->withInput();
        }

        $uploadedFile = null;
        if ($request->hasFile('student_supervisor_eval_file')) {
            if ($request->file('student_supervisor_eval_file')->isValid()) {
                $uploadedFile = \Illuminate\Support\Facades\Storage::putFile('supervisor-evals', $request->file('student_supervisor_eval_file'));
            }
        }

        $evalEntry = \App\Models\StudentChecklist::create([
            'section_id' => $section->id,
            'student_id' => $user->id,
            'item' => 'Supervisor evaluation',
            'student_supervisor_eval_file' => $uploadedFile,
            'student_supervisor_eval_grade' => $validated['student_supervisor_eval_grade'],
            'student_supervisor_eval_submitted_at' => now(),
            'faculty_status' => 'pending',
        ]);

        return redirect('/student/supervisor-eval')->with('success', 'Supervisor evaluation submitted successfully. Pending faculty review.');
    });

    // Certificate of Completion (COC) Routes for Students
    Route::get('/coc', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        $section = \App\Models\Section::find($user->section_id);
        $entries = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', 'Certificate of completion')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('student.coc', ['user' => $user, 'section' => $section, 'entries' => $entries]);
    });

    Route::get('/coc/create', function () {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }
        $section = \App\Models\Section::find($user->section_id);
        return view('student.coc_form', ['user' => $user, 'section' => $section]);
    });

    Route::post('/coc', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') {
            return redirect('/admin/dashboard')->withErrors(['auth' => 'Unauthorized access.']);
        }

        // Receipt of OJT Kit must be submitted before Certificate of Completion
        if (!\App\Models\StudentChecklist::where('student_id', $user->id)->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists()) {
            return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock Certificate of Completion.');
        }

        $validated = $request->validate([
            'student_coc_file' => 'required|file|max:5120',
            'student_coc_signed_by' => 'required|string|max:255',
            'student_coc_company' => 'required|string|max:255',
            'student_coc_date_issued' => 'required|date',
            'student_coc_receive_date' => 'nullable|date',
        ]);

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) {
            return back()->withErrors(['error' => 'No section assigned.'])->withInput();
        }

        $uploadedFile = null;
        if ($request->hasFile('student_coc_file') && $request->file('student_coc_file')->isValid()) {
            $uploadedFile = \Illuminate\Support\Facades\Storage::putFile('coc-files', $request->file('student_coc_file'));
        }

        \App\Models\StudentChecklist::create([
            'section_id' => $section->id,
            'student_id' => $user->id,
            'item' => 'Certificate of completion',
            'student_coc_file' => $uploadedFile,
            'student_coc_signed_by' => $validated['student_coc_signed_by'],
            'student_coc_company' => $validated['student_coc_company'],
            'student_coc_date_issued' => $validated['student_coc_date_issued'],
            'student_coc_receive_date' => $validated['student_coc_receive_date'] ?? null,
            'student_coc_submitted_at' => now(),
            'faculty_status' => 'pending',
        ]);

        return redirect('/student/coc')->with('success', 'Certificate of Completion submitted successfully. Pending faculty review.');
    });

    // Checklist Overview
    // Canonical ordered checklist items — single source of truth
    $canonicalChecklistItems = [
        ['label' => 'Registration card',         'icon' => 'fas fa-id-card',            'description' => 'Submit your registration card to begin the OJT process.',              'url' => '/student/checklist/Registration card/submit'],
        ['label' => 'Medical Record',            'icon' => 'fas fa-notes-medical',      'description' => 'Upload your medical/physical examination results.',                    'url' => '/student/checklist/Medical Record/submit'],
        ['label' => 'Receipt of OJT Kit',        'icon' => 'fas fa-receipt',            'description' => 'Upload proof of payment for your OJT kit.',                           'url' => '/student/checklist/Receipt of OJT Kit/submit'],
        ['label' => 'Waiver',                    'icon' => 'fas fa-file-signature',     'description' => 'Upload your signed waiver form with guardian details.',                'url' => '/student/checklist/Waiver/submit'],
        ['label' => 'Endorsement letter',        'icon' => 'fas fa-envelope-open-text', 'description' => 'Upload the endorsement letter issued by your school.',                 'url' => '/student/checklist/Endorsement letter/submit'],
        ['label' => 'MOA',                       'icon' => 'fas fa-handshake',          'description' => 'Memorandum of Agreement between your school and the OJT company.',    'url' => '/student/checklist/MOA/submit'],
        ['label' => 'DTR',                       'icon' => 'fas fa-clock',              'description' => 'Submit your weekly Daily Time Record (recurring each week).',          'url' => '/student/dtr'],
        ['label' => 'Weekly report',             'icon' => 'fas fa-file-alt',           'description' => 'Submit your weekly progress and task report.',                         'url' => '/student/weekly-report'],
        ['label' => 'Monthly appraisal',         'icon' => 'fas fa-star',               'description' => 'Submit your monthly performance appraisal.',                           'url' => '/student/monthly-appraisal'],
        ['label' => 'Supervisor evaluation',     'icon' => 'fas fa-user-check',         'description' => 'Upload the evaluation form completed by your OJT supervisor.',         'url' => '/student/supervisor-eval'],
        ['label' => 'Certificate of completion', 'icon' => 'fas fa-certificate',        'description' => 'Upload your Certificate of Completion issued by the company.',         'url' => '/student/coc'],
    ];

    Route::get('/checklist', function () use ($canonicalChecklistItems) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');

        $labels = collect($canonicalChecklistItems)->pluck('label')->toArray();

        $allEntries = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('section_id', $user->section_id)
            ->whereIn('item', $labels)
            ->orderBy('created_at', 'desc')
            ->get();

        // Latest entry per item
        $entryByItem = [];
        foreach ($labels as $label) {
            $entryByItem[$label] = $allEntries->where('item', $label)->first();
        }

        // Locking rules:
        // - Items 0-2 (Registration card, Medical Record, Receipt of OJT Kit): sequential, each requires the previous faculty-approved
        // - Items 3+ (Waiver onwards): all unlock once Receipt of OJT Kit is submitted by the student
        $approvedLabels = $allEntries->where('faculty_status', 'approved')->pluck('item')->unique()->toArray();
        $receiptSubmitted = $allEntries->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->isNotEmpty();
        $locked = [];
        foreach ($canonicalChecklistItems as $i => $item) {
            if ($i === 0) {
                $locked[$item['label']] = false;
            } elseif ($i <= 2) {
                $prevLabel = $canonicalChecklistItems[$i - 1]['label'];
                $locked[$item['label']] = !in_array($prevLabel, $approvedLabels);
            } else {
                $locked[$item['label']] = !$receiptSubmitted;
            }
        }

        return view('student.checklist', [
            'user'           => $user,
            'checklistItems' => $canonicalChecklistItems,
            'entryByItem'    => $entryByItem,
            'locked'         => $locked,
        ]);
    });

    // Submit form for one-time checklist items (items 1–6)
    Route::get('/checklist/{item}/submit', function ($item) use ($canonicalChecklistItems) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');

        $itemLabel = urldecode($item);
        $submitItems = ['Registration card', 'Medical Record', 'Receipt of OJT Kit', 'Waiver', 'Endorsement letter', 'MOA'];
        if (!in_array($itemLabel, $submitItems)) {
            return redirect('/student/checklist');
        }

        // Check if this item is locked
        $labels = collect($canonicalChecklistItems)->pluck('label')->toArray();
        $itemIndex = array_search($itemLabel, $labels);
        if ($itemIndex > 0 && $itemIndex <= 2) {
            $prevLabel = $labels[$itemIndex - 1];
            $prevApproved = \App\Models\StudentChecklist::where('student_id', $user->id)
                ->where('item', $prevLabel)->where('faculty_status', 'approved')->exists();
            if (!$prevApproved) {
                return redirect('/student/checklist')->with('error', "Complete and get \"$prevLabel\" approved first.");
            }
        } elseif ($itemIndex > 2) {
            $receiptSubmitted = \App\Models\StudentChecklist::where('student_id', $user->id)
                ->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists();
            if (!$receiptSubmitted) {
                return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock this step.');
            }
        }

        $entry = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', $itemLabel)->orderBy('created_at', 'desc')->first();

        return view('student.checklist_submit', ['user' => $user, 'item' => $itemLabel, 'entry' => $entry]);
    });

    Route::post('/checklist/{item}/submit', function (\Illuminate\Http\Request $request, $item) use ($canonicalChecklistItems) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');

        $itemLabel = urldecode($item);
        $submitItems = ['Registration card', 'Medical Record', 'Receipt of OJT Kit', 'Waiver', 'Endorsement letter', 'MOA'];
        if (!in_array($itemLabel, $submitItems)) return redirect('/student/checklist');

        $section = \App\Models\Section::find($user->section_id);
        if (!$section) return back()->withErrors(['error' => 'No section assigned.'])->withInput();

        // Lock check
        $labels = collect($canonicalChecklistItems)->pluck('label')->toArray();
        $itemIndex = array_search($itemLabel, $labels);
        if ($itemIndex > 0 && $itemIndex <= 2) {
            $prevLabel = $labels[$itemIndex - 1];
            $prevApproved = \App\Models\StudentChecklist::where('student_id', $user->id)
                ->where('item', $prevLabel)->where('faculty_status', 'approved')->exists();
            if (!$prevApproved) {
                return redirect('/student/checklist')->with('error', "Complete and get \"$prevLabel\" approved first.");
            }
        } elseif ($itemIndex > 2) {
            $receiptSubmitted = \App\Models\StudentChecklist::where('student_id', $user->id)
                ->where('item', 'Receipt of OJT Kit')->whereNotNull('student_submitted_at')->exists();
            if (!$receiptSubmitted) {
                return redirect('/student/checklist')->with('error', 'Submit your Receipt of OJT Kit first to unlock this step.');
            }
        }

        $data = ['section_id' => $section->id, 'student_id' => $user->id, 'item' => $itemLabel,
                 'student_submitted_at' => now(), 'student_encoded_at' => now(), 'faculty_status' => 'pending'];

        if ($itemLabel === 'Registration card') {
            $request->validate(['student_file' => 'nullable|file|max:5120']);
            if ($request->hasFile('student_file') && $request->file('student_file')->isValid()) {
                $data['student_file'] = \Illuminate\Support\Facades\Storage::putFile('checklist/registration-card', $request->file('student_file'));
            }
            $data['student_remarks'] = $request->input('student_remarks');

        } elseif ($itemLabel === 'Medical Record') {
            $request->validate(['student_clinic_name' => 'required|string', 'student_clinic_address' => 'required|string']);
            $data['student_clinic_name'] = $request->input('student_clinic_name');
            $data['student_clinic_address'] = $request->input('student_clinic_address');
            if ($request->hasFile('student_files')) {
                $paths = [];
                foreach ($request->file('student_files') as $f) {
                    if ($f->isValid()) $paths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/medical-record', $f);
                }
                if (!empty($paths)) $data['student_files'] = $paths;
            }

        } elseif ($itemLabel === 'Receipt of OJT Kit') {
            $request->validate(['student_paid_date' => 'required|date', 'student_receipt_number' => 'required|string']);
            $data['student_paid_date'] = $request->input('student_paid_date');
            $data['student_receipt_number'] = $request->input('student_receipt_number');
            if ($request->hasFile('student_files')) {
                $paths = [];
                foreach ($request->file('student_files') as $f) {
                    if ($f->isValid()) $paths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/ojt-kit', $f);
                }
                if (!empty($paths)) $data['student_files'] = $paths;
            }

        } elseif ($itemLabel === 'Waiver') {
            $request->validate(['student_guardian_name' => 'required|string', 'student_guardian_contact' => 'required|string']);
            $data['student_guardian_name']    = $request->input('student_guardian_name');
            $data['student_guardian_contact'] = $request->input('student_guardian_contact');
            $data['student_guardian_email']   = $request->input('student_guardian_email');
            $data['student_guardian_social']  = $request->input('student_guardian_social');
            if ($request->hasFile('student_files')) {
                $paths = [];
                foreach ($request->file('student_files') as $f) {
                    if ($f->isValid()) $paths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/waiver', $f);
                }
                if (!empty($paths)) $data['student_files'] = $paths;
            }

        } elseif ($itemLabel === 'Endorsement letter') {
            $request->validate(['student_endorsement_date' => 'required|date', 'student_start_date' => 'required|date', 'student_supervisor_signed_by' => 'required|string']);
            $data['student_endorsement_date']    = $request->input('student_endorsement_date');
            $data['student_start_date']          = $request->input('student_start_date');
            $data['student_supervisor_signed_by'] = $request->input('student_supervisor_signed_by');
            if ($request->hasFile('student_files')) {
                $paths = [];
                foreach ($request->file('student_files') as $f) {
                    if ($f->isValid()) $paths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/endorsement', $f);
                }
                if (!empty($paths)) $data['student_files'] = $paths;
            }

        } elseif ($itemLabel === 'MOA') {
            $data['student_remarks'] = $request->input('student_remarks');
            if ($request->hasFile('student_files')) {
                $paths = [];
                foreach ($request->file('student_files') as $f) {
                    if ($f->isValid()) $paths[] = \Illuminate\Support\Facades\Storage::putFile('checklist/moa', $f);
                }
                if (!empty($paths)) $data['student_files'] = $paths;
            }
        }

        // Update existing entry or create new one
        $existing = \App\Models\StudentChecklist::where('student_id', $user->id)
            ->where('item', $itemLabel)->orderBy('created_at', 'desc')->first();

        if ($existing) {
            $existing->update($data);
        } else {
            \App\Models\StudentChecklist::create($data);
        }

        return redirect('/student/checklist')->with('success', "\"$itemLabel\" submitted successfully. Pending faculty review.");
    });

    // Announcements
    Route::get('/announcements', function () {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        $announcements = \App\Models\Announcement::active()->with('creator')->latest()->get();
        // Mark all active announcements as read
        foreach ($announcements as $ann) {
            \App\Models\AnnouncementRead::firstOrCreate(
                ['user_id' => $user->id, 'announcement_id' => $ann->id],
                ['read_at' => now()]
            );
        }
        return view('student.announcements', ['user' => $user, 'announcements' => $announcements]);
    });

    // Incident Report
    Route::get('/incident-report', function () {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        $reports = \App\Models\IncidentReport::where('student_id', $user->id)->latest()->get();
        return view('student.incident_report', ['user' => $user, 'reports' => $reports]);
    });

    Route::post('/incident-report', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');

        $validated = $request->validate([
            'type'          => 'required|string',
            'incident_date' => 'required|date',
            'location'      => 'required|string|max:255',
            'description'   => 'required|string',
            'action_taken'  => 'nullable|string',
        ]);

        \App\Models\IncidentReport::create(array_merge($validated, ['student_id' => $user->id]));

        return redirect('/student/incident-report')->with('success', 'Incident report submitted successfully.');
    });

    // FAQ
    Route::get('/faq', function () {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        return view('student.faq', ['user' => $user, 'faqs' => []]);
    });

    // Chatbot AI
    Route::get('/chatbot', function () {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        return view('student.chatbot', ['user' => $user]);
    });

    // Profile Settings
    Route::get('/profile', function () {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');
        return view('student.profile', ['user' => $user]);
    });

    Route::put('/profile', function (\Illuminate\Http\Request $request) {
        $user = session('user');
        if ($user->role !== 'student') return redirect('/admin/dashboard');

        $validated = $request->validate([
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'contact'  => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $updateData = [
            'email'   => $validated['email'],
            'contact' => $validated['contact'] ?? $user->contact,
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = bcrypt($validated['password']);
        }

        \App\Models\User::where('id', $user->id)->update($updateData);

        // Refresh session
        $refreshed = \App\Models\User::find($user->id);
        session(['user' => $refreshed]);

        return redirect('/student/profile')->with('success', 'Profile updated successfully.');
    });
});

