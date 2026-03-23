@extends('layouts.admin')

@section('title', 'Dashboard - Faculty')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-tachometer-alt"></i> Faculty Dashboard</h2>

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

    <!-- Section Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card stat-card stat-students">
                <h5>Assigned Section</h5>
                <h3>{{ $section->name ?? 'No Section' }}</h3>
                <p class="text-muted">{{ $section->school_year ?? '' }} - {{ $section->term ?? '' }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card stat-card stat-faculty">
                <h5>Enrolled Students</h5>
                <h3>{{ $studentsCount }}</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row">
        <div class="col-12">
            <h4 class="mb-3">Quick Actions</h4>
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

    .stat-card {
        padding: 20px;
        text-align: center;
        color: white;
        border-radius: 8px;
    }

    .stat-students {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .stat-faculty {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
</style>
@endsection
