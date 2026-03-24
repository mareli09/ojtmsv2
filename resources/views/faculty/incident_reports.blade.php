@extends('layouts.admin')

@section('title', 'Incident Reports')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link active"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-exclamation-triangle"></i> Incident Reports</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Summary badges --}}
    @php
        $pending     = $reports->where('faculty_status', 'pending')->count();
        $reviewing   = $reports->where('faculty_status', 'reviewing')->count();
        $takenAction = $reports->where('faculty_status', 'taken_action')->count();
        $resolved    = $reports->where('faculty_status', 'resolved')->count();
        $declined    = $reports->where('faculty_status', 'declined')->count();
    @endphp
    <div class="row mb-3">
        <div class="col-auto"><span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock me-1"></i>Pending: {{ $pending }}</span></div>
        <div class="col-auto"><span class="badge bg-info fs-6"><i class="fas fa-search me-1"></i>Reviewing: {{ $reviewing }}</span></div>
        <div class="col-auto"><span class="badge bg-primary fs-6"><i class="fas fa-check-double me-1"></i>Action Taken: {{ $takenAction }}</span></div>
        <div class="col-auto"><span class="badge bg-success fs-6"><i class="fas fa-check-circle me-1"></i>Resolved: {{ $resolved }}</span></div>
        <div class="col-auto"><span class="badge bg-danger fs-6"><i class="fas fa-times-circle me-1"></i>Declined: {{ $declined }}</span></div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>All Reports ({{ $reports->count() }})</span>
            <div class="d-flex gap-2">
                <select id="statusFilter" class="form-select form-select-sm" style="width:auto;">
                    <option value="">All Statuses</option>
                    <option value="pending">Pending</option>
                    <option value="reviewing">Reviewing</option>
                    <option value="taken_action">Action Taken</option>
                    <option value="resolved">Resolved</option>
                    <option value="declined">Declined</option>
                </select>
            </div>
        </div>
        <div class="card-body p-0">
            @if($reports->count() == 0)
            <p class="text-muted p-4 mb-0">No incident reports from your students yet.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="reportsTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Type</th>
                            <th>Incident Date</th>
                            <th>Location</th>
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reports as $report)
                        @php
                            $statusMap = [
                                'pending'      => ['warning', 'text-dark', 'Pending'],
                                'reviewing'    => ['info', 'text-dark', 'Reviewing'],
                                'taken_action' => ['primary', 'text-white', 'Action Taken'],
                                'resolved'     => ['success', 'text-white', 'Resolved'],
                                'declined'     => ['danger', 'text-white', 'Declined'],
                            ];
                            [$bg, $text, $label] = $statusMap[$report->faculty_status] ?? ['secondary', 'text-white', 'Unknown'];
                        @endphp
                        <tr data-status="{{ $report->faculty_status }}">
                            <td>
                                <strong>{{ $report->student?->name ?? 'Unknown' }}</strong><br>
                                <small class="text-muted">{{ $report->student?->email }}</small>
                            </td>
                            <td>{{ $report->type }}</td>
                            <td>{{ $report->incident_date->format('M d, Y') }}</td>
                            <td>{{ $report->location }}</td>
                            <td>{{ $report->created_at->format('M d, Y') }}</td>
                            <td><span class="badge bg-{{ $bg }} {{ $text }}">{{ $label }}</span></td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $report->id }}">
                                    <i class="fas fa-eye me-1"></i>Review
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Review Modals --}}
@foreach($reports as $report)
@php
    $statusMap = [
        'pending'      => ['warning', 'text-dark', 'Pending'],
        'reviewing'    => ['info', 'text-dark', 'Reviewing'],
        'taken_action' => ['primary', 'text-white', 'Action Taken'],
        'resolved'     => ['success', 'text-white', 'Resolved'],
        'declined'     => ['danger', 'text-white', 'Declined'],
    ];
    [$bg, $text, $label] = $statusMap[$report->faculty_status] ?? ['secondary', 'text-white', 'Unknown'];
@endphp
<div class="modal fade" id="reviewModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Incident Report — {{ $report->type }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                {{-- Report Details --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Student:</strong> {{ $report->student?->name ?? 'Unknown' }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ $report->type }}</p>
                        <p class="mb-1"><strong>Incident Date:</strong> {{ $report->incident_date->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Location:</strong> {{ $report->location }}</p>
                        <p class="mb-1"><strong>Submitted:</strong> {{ $report->created_at->format('M d, Y g:i A') }}</p>
                        <p class="mb-1"><strong>Current Status:</strong> <span class="badge bg-{{ $bg }} {{ $text }}">{{ $label }}</span></p>
                    </div>
                </div>

                <h6>Description:</h6>
                <div class="bg-light p-3 rounded mb-3" style="white-space: pre-wrap;">{{ $report->description }}</div>

                @if($report->action_taken)
                <h6>Action Taken by Student:</h6>
                <div class="bg-light p-3 rounded mb-3" style="white-space: pre-wrap;">{{ $report->action_taken }}</div>
                @endif

                @if($report->attachment)
                <p><strong>Attachment:</strong>
                    <a href="{{ route('file.download', ['path' => $report->attachment]) }}" target="_blank" class="ms-1 btn btn-sm btn-outline-secondary">
                        <i class="fas fa-paperclip me-1"></i>{{ basename($report->attachment) }}
                    </a>
                </p>
                @endif

                <hr>

                {{-- Faculty Review Form --}}
                <h6><i class="fas fa-gavel me-1"></i>Faculty Response</h6>
                <form method="POST" action="/faculty/incident-reports/{{ $report->id }}/review">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label"><strong>Update Status</strong> <span class="text-danger">*</span></label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending"      {{ $report->faculty_status === 'pending'      ? 'selected' : '' }}>Pending — Not yet reviewed</option>
                            <option value="reviewing"    {{ $report->faculty_status === 'reviewing'    ? 'selected' : '' }}>Reviewing — Currently looking into it</option>
                            <option value="taken_action" {{ $report->faculty_status === 'taken_action' ? 'selected' : '' }}>Action Taken — Steps have been taken</option>
                            <option value="resolved"     {{ $report->faculty_status === 'resolved'     ? 'selected' : '' }}>Resolved — Incident fully addressed</option>
                            <option value="declined"     {{ $report->faculty_status === 'declined'     ? 'selected' : '' }}>Declined — Report not valid / insufficient basis</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Remarks / Response</strong></label>
                        <textarea name="faculty_remarks" class="form-control" rows="4"
                            placeholder="Provide your response, feedback, or steps taken...">{{ $report->faculty_remarks }}</textarea>
                        @if($report->faculty_reviewed_at)
                        <small class="text-muted">Last updated: {{ $report->faculty_reviewed_at->format('M d, Y g:i A') }}</small>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Save Response
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const val = this.value;
    document.querySelectorAll('#reportsTable tbody tr').forEach(function(row) {
        if (!val || row.dataset.status === val) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
@endsection
