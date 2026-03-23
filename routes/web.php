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
    
    // Fetch test accounts from database
    $testAccounts = \App\Models\User::whereIn('role', ['admin', 'faculty', 'student'])
        ->limit(3)
        ->get()
        ->groupBy('role')
        ->map(fn($group) => $group->first());
    
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
});
