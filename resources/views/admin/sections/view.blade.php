@extends('layouts.admin')

@section('title', 'View Section Details')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link active"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-megaphone"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <a href="/admin/sections" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2><i class="fas fa-book"></i> Section Details</h2>
    </div>

    <div class="row">
        <!-- Faculty & Students Panel (Wider) -->
        <div class="col-lg-7">
            <!-- Faculty Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-user-tie"></i> Faculty Advisor</h5>
                </div>
                <div class="card-body">
                    @if($section->faculty)
                        <div class="d-flex align-items-center mb-3">
                            <div>
                                <h6 style="color: var(--ojtms-primary); font-weight: bold; margin-bottom: 5px;">
                                    {{ $section->faculty->first_name . ' ' . $section->faculty->last_name }}
                                </h6>
                                <p class="text-muted small mb-0">
                                    <strong>Employee ID:</strong> {{ $section->faculty->employee_id ?? 'N/A' }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <strong>Department:</strong> {{ $section->faculty->department ?? 'N/A' }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <strong>Email:</strong> {{ $section->faculty->email ?? 'N/A' }}
                                </p>
                                <p class="text-muted small mb-0">
                                    <strong>Contact:</strong> {{ $section->faculty->contact ?? 'N/A' }}
                                </p>
                                <span class="badge bg-success mt-2">
                                    <i class="fas fa-check-circle"></i> Assigned
                                </span>
                            </div>
                        </div>
                    @else
                        <em class="text-muted">
                            <i class="fas fa-info-circle me-2"></i>Not yet assigned
                        </em>
                        <p class="text-muted small mb-0">Will be assigned during registration</p>
                    @endif
                </div>
            </div>

            <!-- Students List Card -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Enrolled Students</h5>
                    <span class="badge bg-primary">{{ $section->students->count() }}</span>
                </div>
                <div class="card-body">
                    @if($section->students->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($section->students as $student)
                                    <tr>
                                        <td>
                                            <strong>{{ $student->student_id ?? 'N/A' }}</strong>
                                        </td>
                                        <td>
                                            {{ $student->first_name . ' ' . $student->last_name }}
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $student->email }}</small>
                                        </td>
                                        <td>
                                            {{ $student->department ?? '--' }}
                                        </td>
                                        <td>
                                            @php
                                                $statusColor = $student->status === 'active' ? '#27ae60' : '#e74c3c';
                                            @endphp
                                            <span class="badge" style="background-color: {{ $statusColor }}; color: white;">
                                                {{ ucfirst($student->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-inbox text-muted" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p class="text-muted mb-0">No students enrolled yet</p>
                            <small class="text-muted">Students will be added through User Management</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Section Details Panel (Narrower) -->
        <div class="col-lg-5">
            <!-- Section Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Section Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Section Name</h6>
                            <h4 style="color: var(--ojtms-primary); font-weight: bold;">
                                {{ $section->name ?? 'A1' }}
                            </h4>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-12">
                            <h6 class="text-muted mb-2">Status</h6>
                            @php
                                $statusColorView = '#27ae60'; // active
                                if ($section->status === 'inactive') {
                                    $statusColorView = '#e74c3c'; // red
                                } elseif ($section->status === 'completed') {
                                    $statusColorView = '#95a5a6'; // gray
                                }
                            @endphp
                            <span class="badge" style="background-color: {{ $statusColorView }}; color: white; font-size: 1rem; padding: 8px 12px;">
                                {{ ucfirst($section->status ?? 'active') }}
                            </span>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">School Year</h6>
                        <p class="lead">{{ $section->school_year ?? '2025-2026' }}</p>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Term</h6>
                        <p class="lead">
                            <span class="badge" style="background-color: var(--ojtms-accent); color: var(--ojtms-primary); font-size: 0.9rem;">
                                {{ $section->term ?? 'Term 1' }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock"></i> Schedule</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Day</h6>
                        <p class="lead" style="color: var(--ojtms-primary); font-weight: bold;">
                            {{ $section->day ?? 'Monday' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">Start Time</h6>
                        <p class="lead" style="color: var(--ojtms-primary); font-weight: bold;">
                            {{ $section->start_time ?? '08:00' }}
                        </p>
                    </div>
                    <div class="mb-3">
                        <h6 class="text-muted mb-2">End Time</h6>
                        <p class="lead" style="color: var(--ojtms-primary); font-weight: bold;">
                            {{ $section->end_time ?? '12:00' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Room Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-door-open"></i> Room / Location</h5>
                </div>
                <div class="card-body">
                    <h4 style="color: var(--ojtms-primary); font-weight: bold;">
                        {{ $section->room ?? 'Room 101' }}
                    </h4>
                </div>
            </div>

            @if($section->description ?? false)
            <!-- Description Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Description</h5>
                </div>
                <div class="card-body">
                    <p>{{ $section->description }}</p>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="mt-3">
                <a href="/admin/sections/{{ $section->id ?? 1 }}/edit" class="btn btn-warning w-100 mb-2">
                    <i class="fas fa-edit"></i> Edit Section
                </a>
                <a href="/admin/sections" class="btn btn-secondary w-100">
                    <i class="fas fa-arrow-left"></i> Back to Sections
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    .list-group-item {
        background-color: var(--ojtms-light);
        margin-bottom: 8px;
        border-radius: 5px;
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0,1,86,0.08);
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }

    hr {
        border-color: var(--ojtms-light);
    }
</style>
@endsection
