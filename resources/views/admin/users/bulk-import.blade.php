@extends('layouts.admin')

@section('title', 'Bulk Import Users')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link active"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-megaphone"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="/admin/users" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2><i class="fas fa-upload"></i> Bulk Import Users</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Import Users from CSV File</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.bulkImport') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="file" class="form-label">Select CSV File <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('file') is-invalid @enderror" id="file" 
                                name="file" accept=".csv,.txt" required>
                            <small class="form-text text-muted">Accepted formats: CSV, TXT</small>
                            @error('file')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="alert alert-info">
                            <strong><i class="fas fa-info-circle"></i> File Format Information:</strong>
                            <ul class="mb-0 mt-2">
                                <li>First row must contain headers</li>
                                <li>Required columns: first_name, last_name, username, email, password, role</li>
                                <li>Optional columns: middle_name, employee_id, student_id, contact, department, section_id, status</li>
                                <li>Role must be: admin, faculty, or student</li>
                                <li>Status must be: active or inactive (defaults to active)</li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload"></i> Import Users
                            </button>
                            <a href="{{ route('users.downloadSample') }}" class="btn btn-secondary">
                                <i class="fas fa-download"></i> Download Sample File
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            @if($errors->has('file'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-circle me-2"></i>File Error:</strong> 
                {{ $errors->first('file') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-check-circle me-2"></i>Success!</strong> 
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('import_errors'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Import Warnings:</strong>
                <ul class="mb-0 mt-2">
                    @foreach(session('import_errors') as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-file-csv"></i> Sample CSV Format</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Your CSV file should look like this:</p>
                    <div class="bg-light p-2 rounded" style="overflow-x: auto; font-size: 0.85rem;">
                        <code>
first_name,middle_name,last_name,username,email,password,role,employee_id,student_id,contact,department,section_id,status<br>
John,M,Doe,johndoe,john@example.com,password123,faculty,EMP001,,0912345678,IT,1,active<br>
Jane,,Smith,janesmith,jane@example.com,password123,student,,STU001,0987654321,,1,active<br>
Admin,A,User,adminuser,admin@example.com,password123,admin,EMP002,,0912000000,Admin,,,active
                        </code>
                    </div>
                    <a href="{{ route('users.downloadSample') }}" class="btn btn-sm btn-secondary w-100 mt-3">
                        <i class="fas fa-download"></i> Download Template
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-list-check"></i> Checklist</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check-circle text-success"></i> Headers are correct</li>
                        <li><i class="fas fa-check-circle text-success"></i> All required fields filled</li>
                        <li><i class="fas fa-check-circle text-success"></i> Usernames are unique</li>
                        <li><i class="fas fa-check-circle text-success"></i> Emails are unique</li>
                        <li><i class="fas fa-check-circle text-success"></i> Valid email format</li>
                        <li><i class="fas fa-check-circle text-success"></i> Passwords are secure</li>
                        <li><i class="fas fa-check-circle text-success"></i> Section IDs exist</li>
                    </ul>
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
</style>
@endsection
