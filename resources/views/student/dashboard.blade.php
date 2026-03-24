@extends('layouts.admin')

@section('title', 'Dashboard - Student')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-tachometer-alt"></i> Student Dashboard</h2>

    <!-- Welcome Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card" style="border-top: 5px solid var(--ojtms-accent);">
                <div class="card-body">
                    <h3>Welcome, {{ $user->first_name }} {{ $user->last_name }}!</h3>
                    <p class="text-muted">
                        <i class="fas fa-info-circle"></i> You are logged in as <strong>{{ ucfirst($user->role) }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stat-card stat-info">
                <h5>Student ID</h5>
                <h3>{{ $user->student_id ?? 'N/A' }}</h3>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card stat-department">
                <h5>Department</h5>
                <h3>{{ $user->department ?? 'N/A' }}</h3>
            </div>
        </div>
    </div>

    <!-- Section Information -->
    @if($section)
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Assigned Section</h5>
                </div>
                <div class="card-body">
                    <p><strong>Section:</strong> {{ $section->name }}</p>
                    <p><strong>School Year:</strong> {{ $section->school_year }}</p>
                    <p><strong>Term:</strong> {{ $section->term }}</p>
                    <p><strong>Room:</strong> {{ $section->room }}</p>
                    <p><strong>Schedule:</strong> {{ $section->day }} ({{ $section->start_time }} - {{ $section->end_time }})</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Faculty Instructor</h5>
                </div>
                <div class="card-body">
                    @if($faculty)
                        <p><strong>Name:</strong> {{ $faculty->first_name }} {{ $faculty->last_name }}</p>
                        <p><strong>Employee ID:</strong> {{ $faculty->employee_id ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $faculty->email }}</p>
                        <p><strong>Contact:</strong> {{ $faculty->contact ?? 'N/A' }}</p>
                    @else
                        <p class="text-muted"><i class="fas fa-info-circle"></i> No faculty assigned yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle"></i> You are not enrolled in any section yet.
            </div>
        </div>
    </div>
    @endif

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">Quick Actions</h4>
            <a href="/student/dtr" class="btn btn-primary">
                <i class="fas fa-clock"></i> Daily Time Record
            </a>
            <a href="/student/weekly-report" class="btn btn-info">
                <i class="fas fa-file-alt"></i> Weekly Reports
            </a>
            <a href="/student/monthly-appraisal" class="btn btn-warning">
                <i class="fas fa-star"></i> Monthly Appraisal
            </a>
            <a href="/logout" class="btn btn-danger">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
        border-radius: 8px;
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }

    .stat-card {
        padding: 20px;
        text-align: center;
        color: white;
        border-radius: 8px;
    }

    .stat-info {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-department {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
</style>
@endsection
