@extends('layouts.student')

@section('title', 'Supervisor Evaluation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-user-check"></i> Supervisor Evaluation</h2>
        <a href="/student/supervisor-eval/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Submit Evaluation
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(empty($entries) || $entries->count() == 0)
    <div class="alert alert-info">
        No supervisor evaluations submitted yet.
        <a href="/student/supervisor-eval/create" class="alert-link">Submit one now.</a>
    </div>
    @else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date Submitted</th>
                        <th>Grade/Rating</th>
                        <th>File</th>
                        <th>Faculty Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entry->student_supervisor_eval_submitted_at?->format('M d, Y g:i A') ?? '—' }}</td>
                        <td><span class="badge bg-info">{{ $entry->student_supervisor_eval_grade ?? '—' }}</span></td>
                        <td>
                            @if($entry->student_supervisor_eval_file)
                                <a href="{{ route('file.download', ['path' => $entry->student_supervisor_eval_file]) }}" class="badge bg-success text-decoration-none">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @else
                                <span class="text-muted">No file</span>
                            @endif
                        </td>
                        <td>
                            @php $sc = $entry->faculty_status === 'approved' ? 'success' : ($entry->faculty_status === 'declined' ? 'danger' : 'warning'); @endphp
                            <span class="badge bg-{{ $sc }}">{{ ucfirst($entry->faculty_status ?? 'pending') }}</span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#evalModal{{ $entry->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

{{-- Modals outside the table --}}
@foreach($entries as $entry)
<div class="modal fade" id="evalModal{{ $entry->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Supervisor Evaluation Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Date Submitted:</strong> {{ $entry->student_supervisor_eval_submitted_at?->format('M d, Y g:i A') ?? '—' }}</div>
                    <div class="col-md-6"><strong>Grade/Rating:</strong> {{ $entry->student_supervisor_eval_grade ?? '—' }}</div>
                </div>
                @if($entry->student_supervisor_eval_file)
                <p><strong>Submitted File:</strong>
                    <a href="{{ route('file.download', ['path' => $entry->student_supervisor_eval_file]) }}" class="ms-1" target="_blank">
                        <i class="fas fa-download me-1"></i>Download File
                    </a>
                </p>
                @endif
                <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Faculty Status:</strong>
                        @php $sc = $entry->faculty_status === 'approved' ? 'success' : ($entry->faculty_status === 'declined' ? 'danger' : 'warning'); @endphp
                        <span class="badge bg-{{ $sc }} ms-1">{{ ucfirst($entry->faculty_status ?? 'pending') }}</span>
                    </div>
                    @if($entry->faculty_supervisor_eval_reviewed_at)
                    <div class="col-md-6 text-muted small">
                        <i class="fas fa-clock me-1"></i>Reviewed: {{ $entry->faculty_supervisor_eval_reviewed_at->format('M d, Y g:i A') }}
                    </div>
                    @endif
                </div>
                @if($entry->faculty_supervisor_eval_remarks)
                <p><strong>Faculty Remarks:</strong></p>
                <blockquote class="blockquote bg-light p-3 rounded">{{ $entry->faculty_supervisor_eval_remarks }}</blockquote>
                @else
                <p class="text-muted small">No faculty remarks yet.</p>
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
