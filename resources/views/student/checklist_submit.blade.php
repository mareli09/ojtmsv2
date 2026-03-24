@extends('layouts.student')

@section('title', 'Submit – {{ $item }}')

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center gap-2 mb-4">
        <a href="/student/checklist" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Checklist
        </a>
        <h2 class="mb-0"><i class="fas fa-upload me-2" style="color:var(--ojtms-accent);"></i>Submit — {{ $item }}</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($entry && $entry->faculty_status === 'approved')
    {{-- Read-only view for approved items --}}
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> <strong>This item has been approved by your faculty.</strong>
        No further action needed.
    </div>
    <div class="card">
        <div class="card-header">Submission Details</div>
        <div class="card-body">
            <p><strong>Submitted on:</strong> {{ $entry->student_submitted_at?->format('M d, Y g:i A') ?? '—' }}</p>
            <p><strong>Faculty status:</strong> <span class="badge bg-success">Approved</span></p>
            @if($entry->faculty_remarks)
            <p><strong>Faculty remarks:</strong> {{ $entry->faculty_remarks }}</p>
            @endif
            @if($entry->student_file)
            <p><strong>Uploaded file:</strong>
                <a href="{{ route('file.download', ['path' => $entry->student_file]) }}" target="_blank">{{ basename($entry->student_file) }}</a>
            </p>
            @elseif($entry->student_files)
                @foreach($entry->student_files as $f)
                <p><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></p>
                @endforeach
            @endif
        </div>
    </div>
    @else

    @if($entry && $entry->faculty_status === 'declined')
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i> <strong>Declined.</strong>
        @if($entry->faculty_remarks) Reason: <em>{{ $entry->faculty_remarks }}</em>@endif
        Please correct and resubmit below.
    </div>
    @elseif($entry && $entry->faculty_status === 'pending')
    <div class="alert alert-warning">
        <i class="fas fa-clock"></i> <strong>Pending faculty review.</strong>
        You can update your submission below if needed.
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    @if($item === 'Registration card') <i class="fas fa-id-card me-2"></i>
                    @elseif($item === 'Medical Record') <i class="fas fa-notes-medical me-2"></i>
                    @elseif($item === 'Receipt of OJT Kit') <i class="fas fa-receipt me-2"></i>
                    @elseif($item === 'Waiver') <i class="fas fa-file-signature me-2"></i>
                    @elseif($item === 'Endorsement letter') <i class="fas fa-envelope-open-text me-2"></i>
                    @elseif($item === 'MOA') <i class="fas fa-handshake me-2"></i>
                    @endif
                    {{ $item }}
                </div>
                <div class="card-body">
                    <form method="POST" action="/student/checklist/{{ urlencode($item) }}/submit" enctype="multipart/form-data">
                        @csrf

                        {{-- === REGISTRATION CARD === --}}
                        @if($item === 'Registration card')
                        <div class="mb-3">
                            <label class="form-label"><strong>Registration Card File</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_file" class="form-control @error('student_file') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" {{ $entry && $entry->faculty_status !== 'declined' ? '' : 'required' }}>
                            @error('student_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_file)
                            <small class="text-muted">Current file: <a href="{{ route('file.download', ['path' => $entry->student_file]) }}" target="_blank">{{ basename($entry->student_file) }}</a> (upload new to replace)</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks (optional)</label>
                            <textarea name="student_remarks" class="form-control" rows="3" placeholder="Any notes for your faculty...">{{ old('student_remarks', $entry?->student_remarks ?? '') }}</textarea>
                        </div>

                        {{-- === MEDICAL RECORD === --}}
                        @elseif($item === 'Medical Record')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Clinic / Hospital Name</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="student_clinic_name" class="form-control @error('student_clinic_name') is-invalid @enderror"
                                    value="{{ old('student_clinic_name', $entry->student_clinic_name ?? '') }}" required>
                                @error('student_clinic_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Clinic / Hospital Address</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="student_clinic_address" class="form-control @error('student_clinic_address') is-invalid @enderror"
                                    value="{{ old('student_clinic_address', $entry->student_clinic_address ?? '') }}" required>
                                @error('student_clinic_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Medical Record File(s)</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_files[]" class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            @error('student_files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_files)
                            <small class="text-muted">Current files: {{ implode(', ', array_map('basename', $entry->student_files)) }} (upload new to replace)</small>
                            @endif
                        </div>

                        {{-- === RECEIPT OF OJT KIT === --}}
                        @elseif($item === 'Receipt of OJT Kit')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Date Paid</strong> <span class="text-danger">*</span></label>
                                <input type="date" name="student_paid_date" class="form-control @error('student_paid_date') is-invalid @enderror"
                                    value="{{ old('student_paid_date', $entry && $entry->student_paid_date ? \Carbon\Carbon::parse($entry->student_paid_date)->format('Y-m-d') : '') }}" required>
                                @error('student_paid_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Reference / OR Number</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="student_receipt_number" class="form-control @error('student_receipt_number') is-invalid @enderror"
                                    value="{{ old('student_receipt_number', $entry?->student_receipt_number ?? '') }}" required>
                                @error('student_receipt_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Receipt File</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_files[]" class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" multiple>
                            @error('student_files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_files)
                            <small class="text-muted">Current: {{ implode(', ', array_map('basename', $entry->student_files)) }}</small>
                            @endif
                        </div>

                        {{-- === WAIVER === --}}
                        @elseif($item === 'Waiver')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Guardian Name</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="student_guardian_name" class="form-control @error('student_guardian_name') is-invalid @enderror"
                                    value="{{ old('student_guardian_name', $entry?->student_guardian_name ?? '') }}" required>
                                @error('student_guardian_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Guardian Contact Number</strong> <span class="text-danger">*</span></label>
                                <input type="text" name="student_guardian_contact" class="form-control @error('student_guardian_contact') is-invalid @enderror"
                                    value="{{ old('student_guardian_contact', $entry?->student_guardian_contact ?? '') }}" required>
                                @error('student_guardian_contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Guardian Email</label>
                                <input type="email" name="student_guardian_email" class="form-control"
                                    value="{{ old('student_guardian_email', $entry?->student_guardian_email ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Guardian Social / Messenger</label>
                                <input type="text" name="student_guardian_social" class="form-control"
                                    value="{{ old('student_guardian_social', $entry?->student_guardian_social ?? '') }}">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Signed Waiver File(s)</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_files[]" class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png" multiple>
                            @error('student_files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_files)
                            <small class="text-muted">Current: {{ implode(', ', array_map('basename', $entry->student_files)) }}</small>
                            @endif
                        </div>

                        {{-- === ENDORSEMENT LETTER === --}}
                        @elseif($item === 'Endorsement letter')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Endorsement Date</strong> <span class="text-danger">*</span></label>
                                <input type="date" name="student_endorsement_date" class="form-control @error('student_endorsement_date') is-invalid @enderror"
                                    value="{{ old('student_endorsement_date', $entry && $entry->student_endorsement_date ? \Carbon\Carbon::parse($entry->student_endorsement_date)->format('Y-m-d') : '') }}" required>
                                @error('student_endorsement_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>OJT Start Date</strong> <span class="text-danger">*</span></label>
                                <input type="date" name="student_start_date" class="form-control @error('student_start_date') is-invalid @enderror"
                                    value="{{ old('student_start_date', $entry && $entry->student_start_date ? \Carbon\Carbon::parse($entry->student_start_date)->format('Y-m-d') : '') }}" required>
                                @error('student_start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Signed By (Supervisor/Dean)</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="student_supervisor_signed_by" class="form-control @error('student_supervisor_signed_by') is-invalid @enderror"
                                value="{{ old('student_supervisor_signed_by', $entry?->student_supervisor_signed_by ?? '') }}" required>
                            @error('student_supervisor_signed_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>Endorsement Letter File(s)</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_files[]" class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            @error('student_files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_files)
                            <small class="text-muted">Current: {{ implode(', ', array_map('basename', $entry->student_files)) }}</small>
                            @endif
                        </div>

                        {{-- === MOA === --}}
                        @elseif($item === 'MOA')
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-info-circle"></i> The MOA may require multiple signings. Upload the latest signed version.
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><strong>MOA File(s)</strong> <span class="text-danger">*</span></label>
                            <input type="file" name="student_files[]" class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            @error('student_files')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            @if($entry && $entry->student_files)
                            <small class="text-muted">Current: {{ implode(', ', array_map('basename', $entry->student_files)) }}</small>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Remarks (optional)</label>
                            <textarea name="student_remarks" class="form-control" rows="3"
                                placeholder="e.g., 'Initial submission', 'Revised after company feedback'...">{{ old('student_remarks', $entry?->student_remarks ?? '') }}</textarea>
                        </div>
                        @endif

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i>
                                {{ ($entry && $entry->student_submitted_at) ? 'Update Submission' : 'Submit' }}
                            </button>
                            <a href="/student/checklist" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
