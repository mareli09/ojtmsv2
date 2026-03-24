@extends('layouts.student')

@section('title', 'Incident Report')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-exclamation-triangle"></i> Incident Report</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header bg-danger text-white">
                    <i class="fas fa-plus-circle me-2"></i>Submit New Incident Report
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="/student/incident-report" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><strong>Incident Type</strong> <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- Select type --</option>
                                <option value="Accident" {{ old('type') == 'Accident' ? 'selected' : '' }}>Accident / Injury</option>
                                <option value="Misconduct" {{ old('type') == 'Misconduct' ? 'selected' : '' }}>Misconduct / Harassment</option>
                                <option value="Property Damage" {{ old('type') == 'Property Damage' ? 'selected' : '' }}>Property Damage</option>
                                <option value="Health Issue" {{ old('type') == 'Health Issue' ? 'selected' : '' }}>Health Issue</option>
                                <option value="Security Concern" {{ old('type') == 'Security Concern' ? 'selected' : '' }}>Security Concern</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Date of Incident</strong> <span class="text-danger">*</span></label>
                            <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror"
                                value="{{ old('incident_date') }}" required>
                            @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Location</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location') }}" placeholder="e.g., Company office, Worksite floor 2" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Description</strong> <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="5" placeholder="Describe what happened in detail..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Action Taken (if any)</strong></label>
                            <textarea name="action_taken" class="form-control" rows="3"
                                placeholder="Describe any immediate actions taken...">{{ old('action_taken') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Attachment / Evidence</strong> <span class="text-muted">(Optional)</span></label>
                            <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4,.mov">
                            <small class="text-muted"><i class="fas fa-info-circle"></i> Max 10MB. Supported: PDF, DOC, DOCX, JPG, PNG, MP4, MOV</small>
                            @error('attachment')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <div id="attachPreview" class="mt-2"></div>
                        </div>

                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-paper-plane"></i> Submit Report
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>My Submitted Reports ({{ $reports->count() }})
                </div>
                @if($reports->count() == 0)
                <div class="card-body">
                    <p class="text-muted mb-0">No reports submitted yet.</p>
                </div>
                @else
                <div class="list-group list-group-flush">
                    @foreach($reports as $report)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <strong>{{ $report->type }}</strong>
                            @php
                                $statusMap = [
                                    'pending'      => ['warning', 'Pending'],
                                    'reviewing'    => ['info', 'Reviewing'],
                                    'taken_action' => ['primary', 'Action Taken'],
                                    'resolved'     => ['success', 'Resolved'],
                                    'declined'     => ['danger', 'Declined'],
                                ];
                                [$color, $label] = $statusMap[$report->faculty_status] ?? ['secondary', 'Unknown'];
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ $label }}</span>
                        </div>
                        <small class="text-muted">{{ $report->incident_date->format('M d, Y') }} &mdash; {{ $report->location }}</small><br>
                        <small>{{ Str::limit($report->description, 80) }}</small>
                        @if($report->attachment)
                        <br><a href="{{ route('file.download', ['path' => $report->attachment]) }}" target="_blank" class="small text-primary">
                            <i class="fas fa-paperclip"></i> View Attachment
                        </a>
                        @endif
                        @if($report->faculty_remarks)
                        <div class="mt-2 p-2 bg-light rounded">
                            <small><strong>Faculty Response:</strong> {{ $report->faculty_remarks }}</small>
                            @if($report->faculty_reviewed_at)
                            <br><small class="text-muted">{{ $report->faculty_reviewed_at->format('M d, Y g:i A') }}</small>
                            @endif
                        </div>
                        @endif
                        <div class="d-flex gap-1 mt-2 flex-wrap">
                            <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#reportModal{{ $report->id }}">
                                <i class="fas fa-eye"></i> View
                            </button>
                            @if($report->faculty_status === 'pending')
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $report->id }}">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <form method="POST" action="/student/incident-report/{{ $report->id }}" class="d-inline"
                                onsubmit="return confirm('Delete this report? This cannot be undone.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modals --}}
@foreach($reports as $report)
@php
    $statusMap = [
        'pending'      => ['warning', 'Pending'],
        'reviewing'    => ['info', 'Reviewing'],
        'taken_action' => ['primary', 'Action Taken'],
        'resolved'     => ['success', 'Resolved'],
        'declined'     => ['danger', 'Declined'],
    ];
    [$color, $label] = $statusMap[$report->faculty_status] ?? ['secondary', 'Unknown'];
@endphp
<div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i>Incident Report — {{ $report->type }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Type:</strong> {{ $report->type }}</div>
                    <div class="col-md-6"><strong>Date:</strong> {{ $report->incident_date->format('M d, Y') }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Location:</strong> {{ $report->location }}</div>
                    <div class="col-md-6"><strong>Submitted:</strong> {{ $report->created_at->format('M d, Y g:i A') }}</div>
                </div>
                <h6 class="mt-3">Description:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $report->description }}</div>
                @if($report->action_taken)
                <h6>Action Taken:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $report->action_taken }}</div>
                @endif
                @if($report->attachment)
                <p><strong>Attachment:</strong>
                    <a href="{{ route('file.download', ['path' => $report->attachment]) }}" target="_blank" class="ms-1">
                        <i class="fas fa-paperclip me-1"></i>{{ basename($report->attachment) }}
                    </a>
                </p>
                @endif
                <hr>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <strong>Faculty Status:</strong>
                    <span class="badge bg-{{ $color }}">{{ $label }}</span>
                    @if($report->faculty_reviewed_at)
                    <small class="text-muted ms-2">Reviewed: {{ $report->faculty_reviewed_at->format('M d, Y g:i A') }}</small>
                    @endif
                </div>
                @if($report->faculty_remarks)
                <h6>Faculty Remarks:</h6>
                <div class="bg-light p-3 rounded">{{ $report->faculty_remarks }}</div>
                @else
                <p class="text-muted small">Awaiting faculty response...</p>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach

{{-- Edit Modals (pending only) --}}
@foreach($reports->where('faculty_status', 'pending') as $report)
<div class="modal fade" id="editModal{{ $report->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Incident Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/student/incident-report/{{ $report->id }}" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Incident Type</strong> <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="Accident"         {{ $report->type === 'Accident'         ? 'selected' : '' }}>Accident / Injury</option>
                            <option value="Misconduct"       {{ $report->type === 'Misconduct'       ? 'selected' : '' }}>Misconduct / Harassment</option>
                            <option value="Property Damage"  {{ $report->type === 'Property Damage'  ? 'selected' : '' }}>Property Damage</option>
                            <option value="Health Issue"     {{ $report->type === 'Health Issue'     ? 'selected' : '' }}>Health Issue</option>
                            <option value="Security Concern" {{ $report->type === 'Security Concern' ? 'selected' : '' }}>Security Concern</option>
                            <option value="Other"            {{ $report->type === 'Other'            ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Date of Incident</strong> <span class="text-danger">*</span></label>
                        <input type="date" name="incident_date" class="form-control"
                            value="{{ $report->incident_date->format('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Location</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="location" class="form-control"
                            value="{{ $report->location }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Description</strong> <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control" rows="4" required>{{ $report->description }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Action Taken (if any)</strong></label>
                        <textarea name="action_taken" class="form-control" rows="3">{{ $report->action_taken }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Replace Attachment</strong> <span class="text-muted">(Optional — leave blank to keep existing)</span></label>
                        @if($report->attachment)
                        <p class="mb-1"><small class="text-muted"><i class="fas fa-paperclip me-1"></i>Current: {{ basename($report->attachment) }}</small></p>
                        @endif
                        <input type="file" name="attachment" class="form-control"
                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.mp4,.mov">
                        <small class="text-muted">Max 10MB.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
document.querySelector('[name=attachment]').addEventListener('change', function() {
    const preview = document.getElementById('attachPreview');
    if (this.files.length > 0) {
        const f = this.files[0];
        preview.innerHTML = `<div class="alert alert-info mb-0 mt-2"><i class="fas fa-paperclip me-1"></i><strong>${f.name}</strong> (${(f.size/1024).toFixed(1)} KB)</div>`;
    } else {
        preview.innerHTML = '';
    }
});
</script>
@endsection
