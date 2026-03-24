@extends('layouts.admin')

@section('title', 'Submit Certificate of Completion')

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
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-certificate"></i> Submit Certificate of Completion</h4>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>There were errors:</strong>
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="/student/coc" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="company" class="form-label"><strong>Company</strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_coc_company') is-invalid @enderror"
                                    id="company" name="student_coc_company"
                                    value="{{ old('student_coc_company') }}"
                                    placeholder="e.g., ABC Corporation" required>
                                @error('student_coc_company')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Name of the company that issued the COC.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="signed_by" class="form-label"><strong>Signed By</strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_coc_signed_by') is-invalid @enderror"
                                    id="signed_by" name="student_coc_signed_by"
                                    value="{{ old('student_coc_signed_by') }}"
                                    placeholder="e.g., John Doe, HR Manager" required>
                                @error('student_coc_signed_by')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Name and position of the signatory.</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="date_issued" class="form-label"><strong>COC Date Issued</strong> <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('student_coc_date_issued') is-invalid @enderror"
                                    id="date_issued" name="student_coc_date_issued"
                                    value="{{ old('student_coc_date_issued') }}" required>
                                @error('student_coc_date_issued')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Date the certificate was issued by the company.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="receive_date" class="form-label"><strong>Receive / Revise Date</strong></label>
                                <input type="date" class="form-control @error('student_coc_receive_date') is-invalid @enderror"
                                    id="receive_date" name="student_coc_receive_date"
                                    value="{{ old('student_coc_receive_date') }}">
                                @error('student_coc_receive_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Date you received or last revised the COC (optional).</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <label for="file" class="form-label"><strong>COC File</strong> <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('student_coc_file') is-invalid @enderror"
                                    id="file" name="student_coc_file" required
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('student_coc_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Max 5MB (PDF, DOC, DOCX, JPG, PNG). Upload the signed certificate.</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-info-circle"></i> Important Information</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Submission date will be automatically recorded.</li>
                                        <li>The COC must be signed by an authorized company representative.</li>
                                        <li>Ensure all details match the physical certificate.</li>
                                        <li>Your faculty will review and provide remarks.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane"></i> Submit Certificate of Completion
                                </button>
                                <a href="/student/coc" class="btn btn-secondary btn-lg w-100 mt-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
