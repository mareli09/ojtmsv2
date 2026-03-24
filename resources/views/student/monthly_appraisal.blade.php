@extends('layouts.admin')

@section('title', 'Monthly Appraisals')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link active"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-star"></i> Monthly Appraisals</h2>
        <a href="/student/monthly-appraisal/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit New Appraisal</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($appraisals->count() > 0)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Your Monthly Appraisal Submissions ({{ $appraisals->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Month</th>
                            <th>Grade/Rating</th>
                            <th>Evaluated by</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Faculty Feedback</th>
                            <th>Submitted Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appraisals as $appraisal)
                            <tr>
                                <td><strong>{{ $appraisal->student_appraisal_month ?? 'N/A' }}</strong></td>
                                <td>
                                    @if($appraisal->student_appraisal_grade_rating)
                                        <span class="badge bg-secondary">{{ $appraisal->student_appraisal_grade_rating }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $appraisal->student_appraisal_evaluated_by ?? 'Not specified' }}</small>
                                </td>
                                <td>
                                    @if($appraisal->student_appraisal_file)
                                        <a href="/{{ ltrim($appraisal->student_appraisal_file, '/') }}" target="_blank" class="badge bg-info text-decoration-none">
                                            <i class="fas fa-file"></i> View
                                        </a>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appraisal->faculty_status === 'approved')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                    @elseif($appraisal->faculty_status === 'declined')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Revision Needed</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($appraisal->faculty_appraisal_remarks)
                                        <small class="text-muted">{{ Str::limit($appraisal->faculty_appraisal_remarks, 40) }}</small>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td>{{ $appraisal->student_appraisal_submitted_at?->format('M d, Y') ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#appraisalModal{{ $appraisal->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="appraisalModal{{ $appraisal->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Monthly Appraisal - {{ $appraisal->student_appraisal_month }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Month:</strong> {{ $appraisal->student_appraisal_month ?? 'N/A' }}</p>
                                            
                                            <hr>
                                            
                                            @if($appraisal->student_appraisal_feedback)
                                                <h6><strong>Your Feedback:</strong></h6>
                                                <div class="bg-light p-3 rounded mb-3">
                                                    {{ $appraisal->student_appraisal_feedback }}
                                                </div>
                                            @endif

                                            <p><strong>Grade/Rating:</strong> 
                                                @if($appraisal->student_appraisal_grade_rating)
                                                    <span class="badge bg-secondary">{{ $appraisal->student_appraisal_grade_rating }}</span>
                                                @else
                                                    Not provided
                                                @endif
                                            </p>
                                            <p><strong>Evaluated by:</strong> {{ $appraisal->student_appraisal_evaluated_by ?? 'Not specified' }}</p>

                                            @if($appraisal->student_appraisal_file)
                                                <h6><strong>Uploaded File:</strong></h6>
                                                <a href="/{{ ltrim($appraisal->student_appraisal_file, '/') }}" target="_blank" class="badge bg-info text-decoration-none p-2">
                                                    <i class="fas fa-file"></i> {{ basename($appraisal->student_appraisal_file) }}
                                                </a>
                                            @endif

                                            <hr>
                                            
                                            <p><strong>Submitted on:</strong> {{ $appraisal->student_appraisal_submitted_at?->format('M d, Y @ H:i') ?? 'Not submitted' }}</p>
                                            <p><strong>Status:</strong> 
                                                @if($appraisal->faculty_status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($appraisal->faculty_status === 'declined')
                                                    <span class="badge bg-danger">Revision Needed</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @endif
                                            </p>
                                            @if($appraisal->faculty_appraisal_remarks)
                                                <p><strong>Faculty Feedback:</strong></p>
                                                <div class="bg-light p-3 rounded">
                                                    {{ $appraisal->faculty_appraisal_remarks }}
                                                </div>
                                                <p class="small text-muted mt-2">Reviewed on {{ $appraisal->faculty_appraisal_reviewed_at?->format('M d, Y @ H:i') ?? 'N/A' }}</p>
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
            <i class="fas fa-info-circle"></i> <strong>No appraisals submitted yet.</strong> Start by submitting your first monthly appraisal.
        </div>
        <a href="/student/monthly-appraisal/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit First Appraisal</a>
    @endif
</div>
@endsection
