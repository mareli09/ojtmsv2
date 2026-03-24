@extends('layouts.student')

@section('title', 'Daily Time Record')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-clock"></i> Daily Time Record (DTR)</h2>
        <a href="/student/dtr/create" class="btn btn-primary"><i class="fas fa-plus-circle"></i> Submit New DTR</a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($dtrEntries->count() > 0)
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Your DTR Submissions ({{ $dtrEntries->count() }})</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Week</th>
                        <th>Hours</th>
                        <th>Validated by</th>
                        <th>Status</th>
                        <th>Submitted</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dtrEntries as $entry)
                    <tr>
                        <td><strong>{{ $entry->student_dtr_week ?? 'N/A' }}</strong></td>
                        <td><span class="badge bg-info">{{ $entry->student_dtr_hours ?? 0 }} hrs</span></td>
                        <td>{{ $entry->student_dtr_validated_by ?? '—' }}</td>
                        <td>
                            @if($entry->faculty_status === 'approved')
                                <span class="badge bg-success"><i class="fas fa-check-circle"></i> Approved</span>
                            @elseif($entry->faculty_status === 'declined')
                                <span class="badge bg-danger"><i class="fas fa-times-circle"></i> Declined</span>
                            @else
                                <span class="badge bg-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                            @endif
                        </td>
                        <td>{{ $entry->student_submitted_at?->format('M d, Y') ?? '—' }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-info"
                                data-bs-toggle="modal" data-bs-target="#dtrModal{{ $entry->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @if($entry->faculty_status !== 'approved')
                            <a href="/student/dtr/{{ $entry->id }}/edit" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Summary Card --}}
    @php
        $approvedEntries    = $dtrEntries->where('faculty_status', 'approved');
        $totalApprovedHours = $approvedEntries->sum('student_dtr_hours');
        $targetHours        = $dtrEntries->first()?->faculty_dtr_target_hours ?? 720;
        $remainingHours     = max(0, $targetHours - $totalApprovedHours);
        $pct                = $targetHours > 0 ? min(100, ($totalApprovedHours / $targetHours) * 100) : 0;
    @endphp
    <div class="card">
        <div class="card-header"><h5 class="mb-0">Hours Summary</h5></div>
        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 bg-light rounded">
                        <div class="text-muted small mb-1">Approved Hours</div>
                        <div class="fs-3 fw-bold text-success">{{ $totalApprovedHours }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 bg-light rounded">
                        <div class="text-muted small mb-1">Target Hours</div>
                        <div class="fs-3 fw-bold text-primary">{{ $targetHours }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 bg-light rounded">
                        <div class="text-muted small mb-1">Remaining</div>
                        <div class="fs-3 fw-bold text-{{ $remainingHours > 0 ? 'warning' : 'success' }}">{{ $remainingHours }}</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="text-center p-3 bg-light rounded">
                        <div class="text-muted small mb-1">Submissions</div>
                        <div class="fs-3 fw-bold text-info">{{ $dtrEntries->count() }}</div>
                    </div>
                </div>
            </div>
            <div class="progress" style="height:22px;">
                <div class="progress-bar bg-success" style="width:{{ $pct }}%">
                    {{ number_format($pct, 1) }}%
                </div>
            </div>
            <small class="text-muted">Progress toward {{ $targetHours }}-hour requirement</small>
        </div>
    </div>

    @else
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No DTR submissions yet.
        <a href="/student/dtr/create" class="alert-link">Submit your first DTR now.</a>
    </div>
    @endif
</div>

{{-- Modals — outside the table to avoid invalid HTML nesting --}}
@foreach($dtrEntries as $entry)
<div class="modal fade" id="dtrModal{{ $entry->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-clock me-2"></i>DTR — {{ $entry->student_dtr_week }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Week:</strong> {{ $entry->student_dtr_week ?? '—' }}</div>
                    <div class="col-md-6"><strong>Hours Submitted:</strong> {{ $entry->student_dtr_hours ?? 0 }} hrs</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Validated by:</strong> {{ $entry->student_dtr_validated_by ?? '—' }}</div>
                    <div class="col-md-6"><strong>Submitted on:</strong> {{ $entry->student_submitted_at?->format('M d, Y g:i A') ?? '—' }}</div>
                </div>
                @if($entry->student_remarks)
                <p class="mb-2"><strong>Remarks:</strong> {{ $entry->student_remarks }}</p>
                @endif

                @if(!empty($entry->student_files))
                <div class="mb-3">
                    <strong>Uploaded Files:</strong>
                    <ul class="mt-1 mb-0">
                        @foreach($entry->student_files as $file)
                        <li>
                            <a href="{{ route('file.download', ['path' => $file]) }}" target="_blank">
                                <i class="fas fa-file me-1"></i>{{ basename($file) }}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Faculty Status:</strong>
                        @if($entry->faculty_status === 'approved')
                            <span class="badge bg-success ms-1">Approved</span>
                        @elseif($entry->faculty_status === 'declined')
                            <span class="badge bg-danger ms-1">Declined</span>
                        @else
                            <span class="badge bg-warning ms-1">Pending Review</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Reviewed on:</strong> {{ $entry->faculty_dtr_reviewed_at?->format('M d, Y g:i A') ?? 'Not yet' }}
                    </div>
                </div>
                @if($entry->faculty_remarks)
                <p class="mb-0"><strong>Faculty Remarks:</strong> {{ $entry->faculty_remarks }}</p>
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
