@extends('layouts.student')

@section('title', 'Monthly Appraisals')

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
                                        <a href="{{ route('file.download', ['path' => $appraisal->student_appraisal_file]) }}" target="_blank" class="badge bg-info text-decoration-none">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No appraisals submitted yet.
            <a href="/student/monthly-appraisal/create" class="alert-link">Submit your first appraisal now.</a>
        </div>
    @endif
</div>

{{-- Modals outside the table --}}
@foreach($appraisals as $appraisal)
<div class="modal fade" id="appraisalModal{{ $appraisal->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-star me-2"></i>Monthly Appraisal — {{ $appraisal->student_appraisal_month }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Month:</strong> {{ $appraisal->student_appraisal_month ?? 'N/A' }}</div>
                    <div class="col-md-6"><strong>Evaluated by:</strong> {{ $appraisal->student_appraisal_evaluated_by ?? '—' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Grade/Rating:</strong>
                        @if($appraisal->student_appraisal_grade_rating)
                            <span class="badge bg-secondary ms-1">{{ $appraisal->student_appraisal_grade_rating }}</span>
                        @else — @endif
                    </div>
                    <div class="col-md-6"><strong>Submitted:</strong> {{ $appraisal->student_appraisal_submitted_at?->format('M d, Y g:i A') ?? '—' }}</div>
                </div>
                @if($appraisal->student_appraisal_feedback)
                <h6>Your Feedback:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $appraisal->student_appraisal_feedback }}</div>
                @endif
                @if($appraisal->student_appraisal_file)
                <p><strong>Uploaded File:</strong>
                    <a href="{{ route('file.download', ['path' => $appraisal->student_appraisal_file]) }}" target="_blank" class="ms-1">
                        <i class="fas fa-file me-1"></i>{{ basename($appraisal->student_appraisal_file) }}
                    </a>
                </p>
                @endif
                <hr>
                <p><strong>Status:</strong>
                    @if($appraisal->faculty_status === 'approved') <span class="badge bg-success">Approved</span>
                    @elseif($appraisal->faculty_status === 'declined') <span class="badge bg-danger">Revision Needed</span>
                    @else <span class="badge bg-warning">Pending Review</span>
                    @endif
                </p>
                @if($appraisal->faculty_appraisal_remarks)
                <p><strong>Faculty Feedback:</strong></p>
                <div class="bg-light p-3 rounded">{{ $appraisal->faculty_appraisal_remarks }}</div>
                <small class="text-muted">Reviewed: {{ $appraisal->faculty_appraisal_reviewed_at?->format('M d, Y g:i A') ?? 'N/A' }}</small>
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
