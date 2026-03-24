@extends('layouts.admin')

@section('title', 'Student Checklist')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-user-check"></i> {{ $student->first_name }} {{ $student->last_name }} - Checklist</h2>
        <a href="/faculty/section/{{ $section->id }}/students" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Students
        </a>
    </diV>

    <p class="mb-3"><strong>Section:</strong> {{ $section->name }} | <strong>SY:</strong> {{ $section->school_year }} | <strong>Term:</strong> {{ $section->term }}</p>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Item</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($checklistItems as $index => $item)
                        @php
                            $entry = $entriesByItem[$item] ?? null;
                            $status = $entry ? $entry->faculty_status : 'not submitted';
                            $studentSubmitted = $entry && $entry->student_submitted_at;
                            $statusColor = match($status) {
                                'approved'  => 'success',
                                'declined'  => 'danger',
                                'pending'   => 'warning',
                                default     => 'secondary',
                            };
                            $statusLabel = match($status) {
                                'approved'     => 'Approved',
                                'declined'     => 'Declined',
                                'pending'      => $studentSubmitted ? 'Pending Review' : 'Not Submitted',
                                default        => 'Not Submitted',
                            };
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item }}</td>
                            <td>
                                <span class="badge bg-{{ $statusColor }}">{{ $statusLabel }}</span>
                                @if($entry && $entry->student_submitted_at)
                                <br><small class="text-muted">{{ $entry->student_submitted_at->format('M d, Y') }}</small>
                                @endif
                            </td>
                            <td>
                                <a href="/faculty/section/{{ $section->id }}/students/{{ $student->id }}/checklist/{{ urlencode($item) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Manage
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection