@extends('layouts.admin')

@section('title', isset($user) ? 'Edit User' : 'Create New User')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link active"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="/admin/users" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>{{ isset($user) ? 'Edit User' : 'Create New User' }}</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">User Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($user) ? route('users.update', $user->id) : route('users.store') }}">
                        @csrf

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" {{ isset($user) ? 'disabled' : '' }} required onchange="handleRoleChange()">
                                <option value="">-- Select Role --</option>
                                <option value="admin" {{ (isset($user) && $user->role === 'admin') || old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="faculty" {{ (isset($user) && $user->role === 'faculty') || old('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                                <option value="student" {{ (isset($user) && $user->role === 'student') || old('role') === 'student' ? 'selected' : '' }}>Student</option>
                            </select>
                            @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <!-- ID Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="employee_id" class="form-label">Employee ID <span class="text-muted">(For Admin/Faculty)</span></label>
                                <input type="text" class="form-control" id="employee_id" name="employee_id" 
                                    value="{{ isset($user) ? $user->employee_id : old('employee_id') }}">
                            </div>
                            <div class="col-md-6">
                                <label for="student_id" class="form-label">Student ID <span class="text-muted">(For Students)</span></label>
                                <input type="text" class="form-control" id="student_id" name="student_id" 
                                    value="{{ isset($user) ? $user->student_id : old('student_id') }}">
                            </div>
                        </div>

                        <!-- Name Fields -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" 
                                    value="{{ isset($user) ? $user->first_name : old('first_name') }}" required>
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="middle_name" class="form-label">Middle Name <span class="text-muted">(Optional)</span></label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" 
                                    value="{{ isset($user) ? $user->middle_name : old('middle_name') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" 
                                    value="{{ isset($user) ? $user->last_name : old('last_name') }}" required>
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="department" class="form-label">Department</label>
                                <input type="text" class="form-control" id="department" name="department" 
                                    value="{{ isset($user) ? $user->department : old('department') }}">
                            </div>
                        </div>

                        <!-- Credentials -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="username" class="form-label">Username <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" 
                                    value="{{ isset($user) ? $user->username : old('username') }}" required>
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" 
                                    value="{{ isset($user) ? $user->email : old('email') }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password {{ !isset($user) ? '(Required)' : '(Leave blank to keep current)' }} <span class="text-danger">*</span></label>
                                <div class="input-group position-relative">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" 
                                        {{ !isset($user) ? 'required' : '' }}>
                                    <button type="button" class="btn btn-outline-secondary position-absolute end-0 top-50 translate-middle-y" id="togglePassword" style="border: none; background: none; z-index: 10;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label for="contact" class="form-label">Contact Number</label>
                                <input type="text" class="form-control" id="contact" name="contact" 
                                    value="{{ isset($user) ? $user->contact : old('contact') }}">
                            </div>
                        </div>

                        <!-- Section Assignment (Optional) -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="section_id" class="form-label">Assign Section <span class="text-muted">(Optional)</span></label>
                                <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                                    <option value="">-- Select Section --</option>
                                    @foreach($sections as $section)
                                        @php
                                            // Check if user is faculty role
                                            $isFaculty = (isset($user) && $user->role === 'faculty') || old('role') === 'faculty';
                                            // Check if section already has faculty assigned (and it's not the current user)
                                            $sectionHasFaculty = $section->faculty_id && (!isset($user) || $user->id !== $section->faculty_id);
                                            $isDisabled = $isFaculty && $sectionHasFaculty;
                                        @endphp
                                        <option 
                                            value="{{ $section->id }}" 
                                            {{ (isset($user) && $user->section_id == $section->id) || old('section_id') == $section->id ? 'selected' : '' }}
                                            {{ $isDisabled ? 'disabled' : '' }}
                                        >
                                            {{ $section->name }} - {{ $section->school_year }} ({{ $section->term }})
                                            @if($sectionHasFaculty)
                                                - ✓ Faculty Assigned
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if(old('role') === 'faculty' || (isset($user) && $user->role === 'faculty'))
                                    <small class="text-warning d-block mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        <strong>Faculty Sections:</strong> Sections marked with "✓ Faculty Assigned" are unavailable. Each section can have only ONE faculty member. If you need to reassign faculty, please contact the admin.
                                    </small>
                                @endif
                                @error('section_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-times-circle me-1"></i> {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" {{ (isset($user) && $user->status === 'active') || old('status') === 'active' ? 'selected' : 'selected' }}>Active</option>
                                    <option value="inactive" {{ (isset($user) && $user->status === 'inactive') || old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-between mt-4">
                            <a href="/admin/users" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($user) ? 'Update User' : 'Create User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <strong>Role Details:</strong>
                        <ul class="mt-2 mb-0">
                            <li><strong>Admin:</strong> Full system access</li>
                            <li><strong>Faculty:</strong> Can manage assigned section</li>
                            <li><strong>Student:</strong> Can view enrolled section</li>
                        </ul>
                    </div>

                    @if(isset($user))
                    <div class="card mt-3">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Account Created</h6>
                            <p class="text-sm">{{ $user->created_at->format('M d, Y H:i') }}</p>
                            
                            <h6 class="text-muted mb-2 mt-3">Last Activity</h6>
                            <p class="text-sm">{{ $user->last_activity_at ? $user->last_activity_at->format('M d, Y H:i') : 'Never' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }

    /* Password toggle button styling */
    #togglePassword {
        padding: 0.5rem 0.75rem;
        color: #6c757d;
        cursor: pointer;
        border-radius: 0.25rem;
        transition: all 0.2s ease;
    }

    #togglePassword:hover {
        color: var(--ojtms-primary);
        background-color: rgba(0, 1, 86, 0.05) !important;
    }

    #togglePassword:focus {
        outline: none;
        box-shadow: none;
    }

    .input-group .form-control {
        padding-right: 2.5rem;
    }
</style>

<script>
    // Sections data (populated from PHP)
    const sections = @json($sections);

    function handleRoleChange() {
        const roleSelect = document.getElementById('role');
        const sectionSelect = document.getElementById('section_id');
        const currentRole = roleSelect.value;
        
        // Rebuild section options
        let html = '<option value="">-- Select Section --</option>';
        
        sections.forEach(section => {
            const hasFaculty = section.faculty_id ? true : false;
            const isFaculty = currentRole === 'faculty';
            const isDisabled = isFaculty && hasFaculty;
            
            html += `<option value="${section.id}" ${isDisabled ? 'disabled' : ''}>
                ${section.name} - ${section.school_year} (${section.term})${hasFaculty ? ' - ✓ Faculty Assigned' : ''}
            </option>`;
        });
        
        sectionSelect.innerHTML = html;
        
        // Show/hide faculty assignment warning
        const facultyWarning = document.querySelector('[id="section_id"]').nextElementSibling;
        if (facultyWarning && facultyWarning.classList.contains('text-warning')) {
            if (currentRole === 'faculty') {
                facultyWarning.style.display = 'block';
            } else {
                facultyWarning.style.display = 'none';
            }
        }
    }

    // Password visibility toggle
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle icon
            const icon = this.querySelector('i');
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    }
</script>
@endsection
