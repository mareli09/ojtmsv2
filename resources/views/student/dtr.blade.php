@extends('layouts.admin')

@section('title', 'Daily Time Record (DTR)')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link active"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clock"></i> Daily Time Record (DTR)</h2>
        <a href="/student/dtr/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit New DTR</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($dtrEntries->count() > 0)
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Your DTR Submissions ({{ $dtrEntries->count() }})</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Week</th>
                            <th>Hours Submitted</th>
                            <th>Validated by</th>
                            <th>Status</th>
                            <th>Faculty Remarks</th>
                            <th>Submitted Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dtrEntries as $entry)
                            <tr>
                                <td><strong>{{ $entry->student_dtr_week ?? 'N/A' }}</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $entry->student_dtr_hours ?? 0 }} hrs</span>
                                </td>
                                <td>{{ $entry->student_dtr_validated_by ?? 'Not specified' }}</td>
                                <td>
                                    @if($entry->faculty_status === 'approved')
                                        <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                                    @elseif($entry->faculty_status === 'declined')
                                        <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Declined</span>
                                    @else
                                        <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if($entry->faculty_remarks)
                                        <small class="text-muted">{{ Str::limit($entry->faculty_remarks, 50) }}</small>
                                    @else
                                        <small class="text-muted">—</small>
                                    @endif
                                </td>
                                <td>{{ $entry->student_submitted_at?->format('M d, Y @ H:i') ?? '—' }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#dtrModal{{ $entry->id }}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                </td>
                            </tr>

                            <!-- View Modal -->
                            <div class="modal fade" id="dtrModal{{ $entry->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">DTR Details - {{ $entry->student_dtr_week }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Week:</strong> {{ $entry->student_dtr_week ?? 'N/A' }}</p>
                                            <p><strong>Hours Submitted:</strong> {{ $entry->student_dtr_hours ?? 0 }} hours</p>
                                            <p><strong>Validated by (Supervisor):</strong> {{ $entry->student_dtr_validated_by ?? 'Not specified' }}</p>
                                            <p><strong>Your Remarks:</strong> {{ $entry->student_remarks ?? 'None' }}</p>
                                            
                                            <hr>
                                            
                                            <p><strong>Status:</strong> 
                                                @if($entry->faculty_status === 'approved')
                                                    <span class="badge bg-success">Approved</span>
                                                @elseif($entry->faculty_status === 'declined')
                                                    <span class="badge bg-danger">Declined</span>
                                                @else
                                                    <span class="badge bg-warning">Pending Review</span>
                                                @endif
                                            </p>
                                            @if($entry->faculty_remarks)
                                                <p><strong>Faculty Remarks:</strong> {{ $entry->faculty_remarks }}</p>
                                            @endif
                                            <p><strong>Reviewed on:</strong> {{ $entry->faculty_dtr_reviewed_at?->format('M d, Y @ H:i') ?? 'Not reviewed yet' }}</p>
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

        <!-- Summary Card -->
        <div class="card mt-4">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Status Summary</h5>
            </div>
            <div class="card-body">
                @php
                    $approvedEntries = $dtrEntries->where('faculty_status', 'approved');
                    $totalApprovedHours = $approvedEntries->sum('student_dtr_hours');
                    $targetHours = $dtrEntries->first()?->faculty_dtr_target_hours ?? 720;
                    $remainingHours = max(0, $targetHours - $totalApprovedHours);
                @endphp
                <div class="row g-3">
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted">Total Approved Hours</h6>
                            <h3 class="text-success">{{ $totalApprovedHours }}</h3>
                            <small>hours</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted">Target Hours</h6>
                            <h3 class="text-primary">{{ $targetHours }}</h3>
                            <small>hours</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted">Remaining Hours</h6>
                            <h3 class="text-{{ $remainingHours > 0 ? 'warning' : 'success' }}">{{ $remainingHours }}</h3>
                            <small>hours</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3 bg-light rounded">
                            <h6 class="text-muted">Submissions</h6>
                            <h3 class="text-info">{{ $dtrEntries->count() }}</h3>
                            <small>total</small>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <div class="progress" style="height: 25px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                            style="width: {{ min(100, ($totalApprovedHours / $targetHours) * 100) }}%"
                            aria-valuenow="{{ $totalApprovedHours }}" aria-valuemin="0" aria-valuemax="{{ $targetHours }}">
                            {{ number_format(($totalApprovedHours / $targetHours) * 100, 1) }}%
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">Progress toward {{ $targetHours }} hours requirement</small>
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> <strong>No DTR submissions yet.</strong> Start by submitting your first weekly DTR.
        </div>
        <a href="/student/dtr/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit First DTR</a>
    @endif
</div>
@endsection
