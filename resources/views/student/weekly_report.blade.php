@extends('layouts.admin')

@section('title', 'Weekly Reports')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link active"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

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
                                <td>
                                    <small>{{ Str::limit($report->student_weekly_task_description ?? 'N/A', 40) }}</small>
                                </td>
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
                                <td>
                                    @if($report->faculty_weekly_remarks)
                                        <small class="text-muted">{{ Str::limit($report->faculty_weekly_remarks, 40) }}</small>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td>{{ $report->student_weekly_submitted_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Weekly Report - {{ $report->student_weekly_week }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Week:</strong> {{ $report->student_weekly_week ?? 'N/A' }}</p>
                                            
                                            <hr>
                                            
                                            <h6><strong>Task Description:</strong></h6>
                                            <div class="bg-light p-3 rounded mb-3">
                                                {{ $report->student_weekly_task_description ?? 'Not provided' }}
                                            </div>
                                            
                                            <h6><strong>Supervisor Feedback:</strong></h6>
                                            <div class="bg-light p-3 rounded mb-3">
                                                {{ $report->student_weekly_supervisor_feedback ?? 'Not provided' }}
                                            </div>

                                            @if($report->student_weekly_files && count($report->student_weekly_files) > 0)
                                                <h6><strong>Uploaded Files:</strong></h6>
                                                <ul class="list-group mb-3">
                                                    @foreach($report->student_weekly_files as $file)
                                                        <li class="list-group-item">
                                                            <a href="/{{ ltrim($file, '/') }}" target="_blank" class="text-decoration-none">
                                                                <i class="fas fa-file"></i> {{ basename($file) }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif

                                            <hr>
                                            
                                            <p><strong>Submitted on:</strong> {{ $report->student_weekly_submitted_at?->format('M d, Y @ H:i') ?? 'Not submitted' }}</p>
                                            <p><strong>Status:</strong> 
                                                @if($report->faculty_status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($report->faculty_status === 'declined')
                                                    <span class="badge bg-danger">Revision Needed</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @endif
                                            </p>
                                            @if($report->faculty_weekly_remarks)
                                                <p><strong>Faculty Feedback:</strong></p>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $report->faculty_weekly_remarks }}
                                                </div>
                                                <p class="small text-muted mt-2">Reviewed on {{ $report->faculty_weekly_reviewed_at?->format('M d, Y @ H:i') ?? 'N/A' }}</p>
                                            @else
                                                <p class="small text-muted">Awaiting faculty feedback...</p>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> <strong>No weekly reports submitted yet.</strong> Start by submitting your first weekly report.
        </div>
        <a href="/student/weekly-report/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit First Report</a>
    @endif
</div>
@endsection
