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
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
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
                        <th>Faculty Feedback</th>
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
                            @php $fb = $entry->faculty_supervisor_eval_remarks ?? $entry->faculty_remarks ?? null; @endphp
                            <small class="text-muted">{{ $fb ? Str::limit($fb, 40) : '—' }}</small>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#evalModal{{ $entry->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @if($entry->faculty_status !== 'approved')
                            <a href="/student/supervisor-eval/{{ $entry->id }}/edit" class="btn btn-sm btn-outline-warning">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="/student/supervisor-eval/{{ $entry->id }}" class="d-inline"
                                onsubmit="return confirm('Archive this evaluation? You can restore it later.')">
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
    @endif

    {{-- Archived Section --}}
    @if($archivedEntries->count() > 0)
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-archive me-2"></i>Archived Evaluations ({{ $archivedEntries->count() }})</h5>
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#archivedEvalList">
                <i class="fas fa-chevron-down"></i>
            </button>
        </div>
        <div class="collapse" id="archivedEvalList">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date Submitted</th>
                            <th>Grade/Rating</th>
                            <th>Archived On</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archivedEntries as $archived)
                        <tr class="table-secondary">
                            <td>{{ $archived->student_supervisor_eval_submitted_at?->format('M d, Y') ?? '—' }}</td>
                            <td>{{ $archived->student_supervisor_eval_grade ?? '—' }}</td>
                            <td>{{ $archived->deleted_at?->format('M d, Y g:i A') ?? '—' }}</td>
                            <td>
                                <form method="POST" action="/student/supervisor-eval/{{ $archived->id }}/restore" class="d-inline">
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
                @php $modalFb = $entry->faculty_supervisor_eval_remarks ?? $entry->faculty_remarks ?? null; @endphp
                @if($modalFb)
                <p><strong>Faculty Remarks:</strong></p>
                <blockquote class="blockquote bg-light p-3 rounded">{{ $modalFb }}</blockquote>
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
