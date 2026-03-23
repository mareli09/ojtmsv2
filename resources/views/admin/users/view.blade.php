@extends('layouts.admin')

@section('title', 'User Details')

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
        <h2><i class="fas fa-user"></i> User Details</h2>
    </div>

    <div class="row">
        <!-- Main Info Panel -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Personal Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Full Name</h6>
                            <p class="lead" style="color: var(--ojtms-primary); font-weight: bold;">
                                {{ $user->first_name . ' ' . ($user->middle_name ? $user->middle_name . ' ' : '') . $user->last_name }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Role</h6>
                            @php
                                $roleBgColor = $user->role === 'admin' ? '#e74c3c' : ($user->role === 'faculty' ? '#3498db' : '#27ae60');
                            @endphp
                            <span class="badge" style="background-color: {{ $roleBgColor }}; color: white; font-size: 1rem; padding: 8px 12px;">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <!-- ID Fields -->
                    @if($user->employee_id)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Employee ID</h6>
                            <p class="lead">{{ $user->employee_id }}</p>
                        </div>
                    </div>
                    @endif

                    @if($user->student_id)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Student ID</h6>
                            <p class="lead">{{ $user->student_id }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Email</h6>
                            <p class="lead">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Contact</h6>
                            <p class="lead">{{ $user->contact ?? 'Not provided' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Department</h6>
                            <p class="lead">{{ $user->department ?? 'Not provided' }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Status</h6>
                            @php
                                $statusColor = $user->status === 'active' ? '#27ae60' : '#e74c3c';
                            @endphp
                            <span class="badge" style="background-color: {{ $statusColor }}; color: white; font-size: 1rem; padding: 8px 12px;">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Assignment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Section Assignment</h5>
                </div>
                <div class="card-body">
                    @if($user->section)
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Assigned Section</h6>
                            <p class="lead">
                                <span class="badge" style="background-color: var(--ojtms-accent); color: var(--ojtms-primary); font-size: 1rem; padding: 8px 12px;">
                                    {{ $user->section->name }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">School Year & Term</h6>
                            <p class="lead">{{ $user->section->school_year }} - {{ $user->section->term }}</p>
                        </div>
                    </div>
                    @else
                    <p class="text-muted">No section assigned yet.</p>
                    @endif
                </div>
            </div>

            <!-- Credentials (Admin Only Info) -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock"></i> Account Credentials</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Username</h6>
                            <p class="lead">{{ $user->username }}</p>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-lock"></i> Password is not displayed for security reasons. Reset password through edit form if needed.
                    </div>
                </div>
            </div>
        </div>

        <!-- Side Panel -->
        <div class="col-lg-4">
            <!-- Activity Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history"></i> Activity Log</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Account Created</h6>
                        <p class="text-sm">{{ $user->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Last Updated</h6>
                        <p class="text-sm">{{ $user->updated_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="mb-0">
                        <h6 class="text-muted mb-2">Last Activity</h6>
                        <p class="text-sm">{{ $user->last_activity_at ? $user->last_activity_at->format('M d, Y H:i') : 'Never logged in' }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Actions</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Archive this user?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Archive User
                        </button>
                    </form>
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

    .text-sm {
        font-size: 0.9rem;
    }
</style>
@endsection
