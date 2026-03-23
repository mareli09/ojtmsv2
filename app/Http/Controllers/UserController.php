<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters
     */
    public function index(Request $request)
    {
        $query = User::query()->where('deleted_at', null);

        // Search by name, username, or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->input('role'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter by section
        if ($request->filled('section_id')) {
            $query->where('section_id', $request->input('section_id'));
        }

        $users = $query->paginate(15);
        $sections = Section::where('deleted_at', null)->get();

        return view('admin.users.index', compact('users', 'sections'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $sections = Section::where('deleted_at', null)->get();
        return view('admin.users.create', compact('sections'));
    }

    /**
     * Store a newly created user in storage
     */
    public function store(Request $request)
    {
        $validated = Validator::make($request->all(), [
            'role' => 'required|in:admin,faculty,student',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:active,inactive',
            'employee_id' => 'nullable|unique:users,employee_id',
            'student_id' => 'nullable|unique:users,student_id',
        ])->validate();

        // Ensure employee_id is set for admin/faculty, student_id for students
        if (in_array($validated['role'], ['admin', 'faculty'])) {
            if (!isset($validated['employee_id']) || empty($validated['employee_id'])) {
                return back()->withErrors(['employee_id' => 'Employee ID is required for this role.']);
            }
        } elseif ($validated['role'] === 'student') {
            if (!isset($validated['student_id']) || empty($validated['student_id'])) {
                return back()->withErrors(['student_id' => 'Student ID is required for this role.']);
            }
        }

        User::create([
            'role' => $validated['role'],
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'contact' => $validated['contact'] ?? null,
            'department' => $validated['department'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'status' => $validated['status'],
            'employee_id' => $validated['employee_id'] ?? null,
            'student_id' => $validated['student_id'] ?? null,
        ]);

        return redirect('/admin/users')->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $sections = Section::where('deleted_at', null)->get();
        return view('admin.users.create', compact('user', 'sections'));
    }

    /**
     * Update the specified user in storage
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username,' . $user->id . '|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'contact' => 'nullable|string|max:20',
            'department' => 'nullable|string|max:255',
            'section_id' => 'nullable|exists:sections,id',
            'status' => 'required|in:active,inactive',
            'employee_id' => 'nullable|unique:users,employee_id,' . $user->id,
            'student_id' => 'nullable|unique:users,student_id,' . $user->id,
        ])->validate();

        $user->update([
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'contact' => $validated['contact'] ?? null,
            'department' => $validated['department'] ?? null,
            'section_id' => $validated['section_id'] ?? null,
            'status' => $validated['status'],
            'employee_id' => $validated['employee_id'] ?? null,
            'student_id' => $validated['student_id'] ?? null,
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    /**
     * Display the specified user
     */
    public function view($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.view', compact('user'));
    }

    /**
     * Soft delete (archive) the specified user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('/admin/users')->with('success', 'User archived successfully.');
    }

    /**
     * Display archived users
     */
    public function archive()
    {
        $users = User::onlyTrashed()->paginate(15);
        return view('admin.users.archive', compact('users'));
    }

    /**
     * Restore an archived user
     */
    public function restore($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();

        return redirect('/admin/users-archive')->with('success', 'User restored successfully.');
    }

    /**
     * Show the bulk import form
     */
    public function bulkImportForm()
    {
        $sections = Section::where('deleted_at', null)->get();
        return view('admin.users.bulk-import', compact('sections'));
    }

    /**
     * Download CSV template for bulk import
     */
    public function downloadSample()
    {
        $headers = [
            'first_name',
            'middle_name',
            'last_name',
            'username',
            'email',
            'password',
            'employee_id',
            'student_id',
            'contact',
            'department',
            'section_id',
            'role',
            'status',
        ];

        $rows = [
            ['John', 'Michael', 'Doe', 'johndoe', 'john@example.com', 'password123', 'FAC001', '', '09123456789', 'Library', '1', 'faculty', 'active'],
            ['Jane', '', 'Smith', 'janesmith', 'jane@example.com', 'password456', '', 'STU001', '09987654321', 'Engineering', '2', 'student', 'active'],
        ];

        $filename = 'users_sample.csv';
        $handle = fopen('php://memory', 'r+');

        // Write headers
        fputcsv($handle, $headers);

        // Write sample rows
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }

        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);

        return response($content)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename=' . $filename);
    }

    /**
     * Process bulk import from CSV file
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $headers = fgetcsv($handle);

        $createdCount = 0;
        $failedCount = 0;
        $errors = [];

        while ($row = fgetcsv($handle)) {
            try {
                $data = array_combine($headers, $row);

                // Validate required fields
                if (empty($data['first_name']) || empty($data['last_name']) || 
                    empty($data['username']) || empty($data['email']) || 
                    empty($data['password']) || empty($data['role'])) {
                    $failedCount++;
                    $errors[] = "Row skipped: Missing required fields";
                    continue;
                }

                // Check if user already exists
                if (User::where('username', $data['username'])->exists() ||
                    User::where('email', $data['email'])->exists()) {
                    $failedCount++;
                    $errors[] = "Row skipped: Username or email already exists";
                    continue;
                }

                User::create([
                    'role' => $data['role'] ?? 'student',
                    'first_name' => $data['first_name'],
                    'middle_name' => $data['middle_name'] ?? null,
                    'last_name' => $data['last_name'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => Hash::make($data['password']),
                    'contact' => $data['contact'] ?? null,
                    'department' => $data['department'] ?? null,
                    'section_id' => $data['section_id'] ?? null,
                    'status' => $data['status'] ?? 'active',
                    'employee_id' => !empty($data['employee_id']) ? $data['employee_id'] : null,
                    'student_id' => !empty($data['student_id']) ? $data['student_id'] : null,
                ]);

                $createdCount++;
            } catch (\Exception $e) {
                $failedCount++;
                $errors[] = "Row skipped: " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "$createdCount users created";
        if ($failedCount > 0) {
            $message .= ", $failedCount failed";
        }

        return redirect('/admin/users')->with('success', $message);
    }
}
