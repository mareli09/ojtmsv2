@extends('layouts.admin')

@section('title', 'Faculty Profile Settings')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link active"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-user-cog"></i> Profile Settings</h2>
    <p class="text-muted">Update your contact details and password.</p>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-circle-check me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- ===== SUMMARY CARD ===== -->
        <div class="col-md-4 mb-4">
            <div class="card text-center h-100">
                <div class="card-body py-4">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--ojtms-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                        <i class="fas fa-user-tie fa-2x" style="color:var(--ojtms-accent);"></i>
                    </div>
                    <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <span class="badge mb-3" style="background:var(--ojtms-accent); color:var(--ojtms-primary);">Faculty</span>

                    <p class="text-muted small mb-1"><i class="fas fa-id-badge me-1"></i> {{ $user->employee_id ?? 'No employee ID' }}</p>
                    <p class="text-muted small mb-1"><i class="fas fa-envelope me-1"></i> {{ $user->email ?? 'N/A' }}</p>
                    <p class="text-muted small mb-1"><i class="fas fa-phone me-1"></i> {{ $user->contact ?? 'N/A' }}</p>
                    <p class="text-muted small mb-3"><i class="fas fa-building me-1"></i> {{ $user->department ?? 'N/A' }}</p>

                    <hr>
                    <p class="mb-1 small text-muted">Assigned Section</p>
                    @if($section)
                        <p class="fw-bold mb-1" style="color:var(--ojtms-primary);">{{ $section->name }}</p>
                        <p class="small text-muted mb-0">
                            {{ $section->day }}<br>
                            {{ $section->room }} &middot; {{ $section->school_year }}
                        </p>
                    @else
                        <p class="small mb-0" style="color:#8a6100;">
                            <i class="fas fa-circle-exclamation me-1"></i>Not assigned &mdash; contact the admin.
                        </p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- ===== PERSONAL DETAILS ===== -->
            <div class="card mb-4">
                <div class="card-header"><i class="fas fa-edit me-2"></i>Update Information</div>
                <div class="card-body">
                    <form method="POST" action="/faculty/profile">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">First Name</label>
                                <input type="text" name="first_name" maxlength="50" required
                                    class="form-control @error('first_name') is-invalid @enderror"
                                    value="{{ old('first_name', $user->first_name) }}">
                                @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" maxlength="50"
                                    class="form-control @error('middle_name') is-invalid @enderror"
                                    value="{{ old('middle_name', $user->middle_name) }}">
                                @error('middle_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Last Name</label>
                                <input type="text" name="last_name" maxlength="50" required
                                    class="form-control @error('last_name') is-invalid @enderror"
                                    value="{{ old('last_name', $user->last_name) }}">
                                @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" maxlength="150" required
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}">
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact" maxlength="20"
                                    class="form-control @error('contact') is-invalid @enderror"
                                    value="{{ old('contact', $user->contact) }}" placeholder="e.g., 09123456789">
                                @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" maxlength="100"
                                    class="form-control @error('department') is-invalid @enderror"
                                    value="{{ old('department', $user->department) }}" placeholder="e.g., College of Computer Studies">
                                @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="{{ $user->username }}" readonly>
                                <small class="text-muted">Managed by the admin.</small>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>

            <!-- ===== PASSWORD ===== -->
            <div class="card">
                <div class="card-header"><i class="fas fa-lock me-2"></i>Change Password</div>
                <div class="card-body">
                    <form method="POST" action="/faculty/password">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" required
                                class="form-control @error('current_password') is-invalid @enderror"
                                placeholder="Enter your current password">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" required
                                    class="form-control @error('password') is-invalid @enderror"
                                    placeholder="At least 6 characters">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" required
                                    class="form-control" placeholder="Repeat the new password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
