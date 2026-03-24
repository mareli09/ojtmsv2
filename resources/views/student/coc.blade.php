@extends('layouts.student')

@section('title', 'Certificate of Completion')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-certificate"></i> Certificate of Completion</h2>
        <a href="/student/coc/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Submit COC
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
        No certificate of completion submitted yet.
        <a href="/student/coc/create" class="alert-link">Submit one now.</a>
    </div>
    @else
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date Submitted</th>
                        <th>Company</th>
                        <th>Signed By</th>
                        <th>Date Issued</th>
                        <th>File</th>
                        <th>Faculty Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td>{{ $entry->student_coc_submitted_at ? \Carbon\Carbon::parse($entry->student_coc_submitted_at)->format('M d, Y') : '—' }}</td>
                        <td>{{ $entry->student_coc_company ?? '—' }}</td>
                        <td>{{ $entry->student_coc_signed_by ?? '—' }}</td>
                        <td>{{ $entry->student_coc_date_issued ? \Carbon\Carbon::parse($entry->student_coc_date_issued)->format('M d, Y') : '—' }}</td>
                        <td>
                            @if($entry->student_coc_file)
                                <a href="{{ route('file.download', ['path' => $entry->student_coc_file]) }}" class="badge bg-success text-decoration-none">
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
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#cocModal{{ $entry->id }}">
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
<div class="modal fade" id="cocModal{{ $entry->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-certificate me-2"></i>Certificate of Completion Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Company:</strong> {{ $entry->student_coc_company ?? '—' }}</div>
                    <div class="col-md-6"><strong>Signed By:</strong> {{ $entry->student_coc_signed_by ?? '—' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Date Issued:</strong>
                        {{ $entry->student_coc_date_issued ? \Carbon\Carbon::parse($entry->student_coc_date_issued)->format('M d, Y') : '—' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Receive / Revise Date:</strong>
                        {{ $entry->student_coc_receive_date ? \Carbon\Carbon::parse($entry->student_coc_receive_date)->format('M d, Y') : '—' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Date Submitted:</strong>
                        {{ $entry->student_coc_submitted_at ? \Carbon\Carbon::parse($entry->student_coc_submitted_at)->format('M d, Y g:i A') : '—' }}
                    </div>
                    <div class="col-md-6">
                        @if($entry->student_coc_file)
                        <strong>File:</strong>
                        <a href="{{ route('file.download', ['path' => $entry->student_coc_file]) }}" target="_blank" class="ms-1">
                            <i class="fas fa-download me-1"></i>Download
                        </a>
                        @endif
                    </div>
                </div>
                <hr>
                <div class="row mb-2">
                    <div class="col-md-6">
                        <strong>Faculty Status:</strong>
                        @php $sc = $entry->faculty_status === 'approved' ? 'success' : ($entry->faculty_status === 'declined' ? 'danger' : 'warning'); @endphp
                        <span class="badge bg-{{ $sc }} ms-1">{{ ucfirst($entry->faculty_status ?? 'pending') }}</span>
                    </div>
                    @if($entry->faculty_coc_reviewed_at)
                    <div class="col-md-6 text-muted small">
                        <i class="fas fa-clock me-1"></i>Reviewed: {{ \Carbon\Carbon::parse($entry->faculty_coc_reviewed_at)->format('M d, Y g:i A') }}
                    </div>
                    @endif
                </div>
                @if($entry->faculty_coc_remarks)
                <p><strong>Faculty Remarks:</strong></p>
                <blockquote class="blockquote bg-light p-3 rounded">{{ $entry->faculty_coc_remarks }}</blockquote>
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
