@extends('layouts.admin')

@section('title', 'Certificate of Completion')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link active"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

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
        No certificate of completion submitted yet. <a href="/student/coc/create">Submit one now</a>
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
                        <th>Faculty Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entries as $entry)
                    <tr>
                        <td>
                            @if($entry->student_coc_submitted_at)
                                {{ \Carbon\Carbon::parse($entry->student_coc_submitted_at)->format('M d, Y g:i A') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $entry->student_coc_company ?? '-' }}</td>
                        <td>{{ $entry->student_coc_signed_by ?? '-' }}</td>
                        <td>
                            @if($entry->student_coc_date_issued)
                                {{ \Carbon\Carbon::parse($entry->student_coc_date_issued)->format('M d, Y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($entry->student_coc_file)
                                <a href="{{ route('file.download', ['path' => $entry->student_coc_file]) }}" class="badge bg-success">
                                    <i class="fas fa-download"></i> Download
                                </a>
                            @else
                                <span class="text-muted">No file</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColor = $entry->faculty_status === 'approved' ? 'success' : ($entry->faculty_status === 'declined' ? 'danger' : 'warning');
                            @endphp
                            <span class="badge bg-{{ $statusColor }}">{{ ucfirst($entry->faculty_status ?? 'pending') }}</span>
                        </td>
                        <td>
                            @if($entry->faculty_coc_remarks)
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#remarksModal{{ $entry->id }}">
                                    <i class="fas fa-comment"></i> View
                                </button>
                                <div class="modal fade" id="remarksModal{{ $entry->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Faculty Remarks</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <blockquote class="blockquote">
                                                    {{ $entry->faculty_coc_remarks }}
                                                </blockquote>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#detailModal{{ $entry->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            <div class="modal fade" id="detailModal{{ $entry->id }}" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Certificate of Completion Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Date Submitted:</strong><br>
                                                    @if($entry->student_coc_submitted_at)
                                                        {{ \Carbon\Carbon::parse($entry->student_coc_submitted_at)->format('M d, Y g:i A') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Company:</strong><br>
                                                    {{ $entry->student_coc_company ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Signed By:</strong><br>
                                                    {{ $entry->student_coc_signed_by ?? '-' }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Date Issued:</strong><br>
                                                    @if($entry->student_coc_date_issued)
                                                        {{ \Carbon\Carbon::parse($entry->student_coc_date_issued)->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <strong>Receive/Revise Date:</strong><br>
                                                    @if($entry->student_coc_receive_date)
                                                        {{ \Carbon\Carbon::parse($entry->student_coc_receive_date)->format('M d, Y') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>Uploaded File:</strong><br>
                                                    @if($entry->student_coc_file)
                                                        <a href="{{ route('file.download', ['path' => $entry->student_coc_file]) }}" class="btn btn-sm btn-success">
                                                            <i class="fas fa-download"></i> Download File
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No file uploaded</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <hr>
                                            <div class="row mb-3">
                                                <div class="col-md-12">
                                                    <strong>Faculty Status:</strong><br>
                                                    @php
                                                        $statusColor = $entry->faculty_status === 'approved' ? 'success' : ($entry->faculty_status === 'declined' ? 'danger' : 'warning');
                                                    @endphp
                                                    <span class="badge bg-{{ $statusColor }} fs-6">{{ ucfirst($entry->faculty_status ?? 'pending') }}</span>
                                                </div>
                                            </div>
                                            @if($entry->faculty_coc_remarks)
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <strong>Faculty Remarks:</strong><br>
                                                    <blockquote class="blockquote">
                                                        {{ $entry->faculty_coc_remarks }}
                                                    </blockquote>
                                                </div>
                                            </div>
                                            @endif
                                            @if($entry->faculty_coc_reviewed_at)
                                            <div class="row mt-3 pt-3 border-top">
                                                <div class="col-md-12 text-muted small">
                                                    <i class="fas fa-clock"></i> Faculty reviewed: {{ \Carbon\Carbon::parse($entry->faculty_coc_reviewed_at)->format('M d, Y g:i A') }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
