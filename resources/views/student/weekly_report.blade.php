@extends('layouts.student')

@section('title', 'Weekly Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-file-alt"></i> Weekly Reports</h2>
        <a href="/student/weekly-report/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit New Report</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($weeklyReports->count() > 0)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Your Weekly Report Submissions ({{ $weeklyReports->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Week</th>
                            <th>Task Summary</th>
                            <th>Files</th>
                            <th>Status</th>
                            <th>Faculty Feedback</th>
                            <th>Submitted Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklyReports as $report)
                            <tr>
                                <td><strong>{{ $report->student_weekly_week ?? 'N/A' }}</strong></td>
                                <td><small>{{ Str::limit($report->student_weekly_task_description ?? 'N/A', 40) }}</small></td>
                                <td>
                                    @if($report->student_weekly_files && count($report->student_weekly_files) > 0)
                                        <span class="badge bg-info">{{ count($report->student_weekly_files) }} file(s)</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($report->faculty_status === 'approved')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                    @elseif($report->faculty_status === 'declined')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Revision Needed</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ $report->faculty_weekly_remarks ? Str::limit($report->faculty_weekly_remarks, 40) : '—' }}</small></td>
                                <td>{{ $report->student_weekly_submitted_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    @if($report->faculty_status !== 'approved')
                                    <a href="/student/weekly-report/{{ $report->id }}/edit" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form method="POST" action="/student/weekly-report/{{ $report->id }}" class="d-inline"
                                        onsubmit="return confirm('Archive this weekly report? You can restore it later.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                                            <i class="fas fa-archive"></i> Archive
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No weekly reports submitted yet.
            <a href="/student/weekly-report/create" class="alert-link">Submit your first report now.</a>
        </div>
    @endif

    {{-- Archived Weekly Reports Section --}}
    @if($archivedWeeklyReports->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-archive me-2"></i>Archived Weekly Reports ({{ $archivedWeeklyReports->count() }})</h5>
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#archivedReportList">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="archivedReportList">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Week</th>
                            <th>Task Summary</th>
                            <th>Archived On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedWeeklyReports as $archived)
                        <tr class="table-secondary">
                            <td><strong>{{ $archived->student_weekly_week ?? '—' }}</strong></td>
                            <td><small>{{ Str::limit($archived->student_weekly_task_description ?? '—', 50) }}</small></td>
                            <td>{{ $archived->deleted_at?->format('M d, Y g:i A') ?? '—' }}</td>
                            <td>
                                <form method="POST" action="/student/weekly-report/{{ $archived->id }}/restore" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- Modals outside the table --}}
@foreach($weeklyReports as $report)
<div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-file-alt me-2"></i>Weekly Report — {{ $report->student_weekly_week }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p><strong>Week:</strong> {{ $report->student_weekly_week ?? 'N/A' }}</p>
                <p><strong>Submitted on:</strong> {{ $report->student_weekly_submitted_at?->format('M d, Y g:i A') ?? '—' }}</p>
                <hr>
                <h6>Task Description:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $report->student_weekly_task_description ?? 'Not provided' }}</div>
                <h6>Supervisor Feedback:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $report->student_weekly_supervisor_feedback ?? 'Not provided' }}</div>
                @if($report->student_weekly_files && count($report->student_weekly_files) > 0)
                <h6>Uploaded Files:</h6>
                <ul class="list-group mb-3">
                    @foreach($report->student_weekly_files as $file)
                    <li class="list-group-item">
                        <a href="{{ route('file.download', ['path' => $file]) }}" target="_blank">
                            <i class="fas fa-file me-1"></i>{{ basename($file) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                @endif
                <hr>
                <p><strong>Status:</strong>
                    @if($report->faculty_status === 'approved') <span class="badge bg-success">Approved</span>
                    @elseif($report->faculty_status === 'declined') <span class="badge bg-danger">Revision Needed</span>
                    @else <span class="badge bg-warning">Pending Review</span>
                    @endif
                </p>
                @if($report->faculty_weekly_remarks)
                <p><strong>Faculty Feedback:</strong></p>
                <div class="bg-light p-3 rounded">{{ $report->faculty_weekly_remarks }}</div>
                <small class="text-muted">Reviewed: {{ $report->faculty_weekly_reviewed_at?->format('M d, Y g:i A') ?? 'N/A' }}</small>
                @else
                <p class="text-muted small">Awaiting faculty feedback...</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection
