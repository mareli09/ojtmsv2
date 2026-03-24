@extends('layouts.student')

@section('title', isset($entry) ? 'Edit DTR' : 'Submit DTR')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-{{ isset($entry) ? 'edit' : 'plus-circle' }}"></i> {{ isset($entry) ? 'Edit DTR — ' . $entry->student_dtr_week : 'Submit Daily Time Record' }}</h2>
        <a href="/student/dtr" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to DTR</a>
    </div>

    @if($entry->faculty_status === 'declined' ?? false)
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i> <strong>Declined.</strong>
        @if($entry->faculty_remarks) Reason: <em>{{ $entry->faculty_remarks }}</em>@endif
        Please correct and resubmit below.
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ isset($entry) ? 'Update DTR Submission' : 'Weekly DTR Submission' }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form method="POST"
                          action="{{ isset($entry) ? '/student/dtr/' . $entry->id : '/student/dtr' }}"
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($entry)) @method('PUT') @endif

                        <div class="mb-3">
                            <label class="form-label">Week <span class="text-danger">*</span></label>
                            <input type="text" name="student_dtr_week" class="form-control @error('student_dtr_week') is-invalid @enderror"
                                placeholder="e.g., Week 1, Week 2 of March..."
                                value="{{ old('student_dtr_week', $entry->student_dtr_week ?? '') }}" required>
                            @error('student_dtr_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Hours Worked <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" name="student_dtr_hours" class="form-control @error('student_dtr_hours') is-invalid @enderror"
                                    placeholder="0.5" step="0.5" min="0" max="60"
                                    value="{{ old('student_dtr_hours', $entry->student_dtr_hours ?? '') }}" required>
                                <span class="input-group-text">hours</span>
                            </div>
                            @error('student_dtr_hours')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            <small class="text-muted">Max 60 hours per submission.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Validated by (Supervisor Name) <span class="text-danger">*</span></label>
                            <input type="text" name="student_dtr_validated_by" class="form-control @error('student_dtr_validated_by') is-invalid @enderror"
                                placeholder="Supervisor's full name"
                                value="{{ old('student_dtr_validated_by', $entry->student_dtr_validated_by ?? '') }}" required>
                            @error('student_dtr_validated_by')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Remarks (Optional)</label>
                            <textarea name="student_remarks" class="form-control" rows="3"
                                placeholder="Any additional notes about this week's work...">{{ old('student_remarks', $entry->student_remarks ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">DTR File(s) {{ isset($entry) ? '' : '<span class="text-danger">*</span>' }}</label>
                            <input type="file" name="student_files[]"
                                class="form-control @error('student_files') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple
                                {{ isset($entry) ? '' : 'required' }}>
                            @error('student_files')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                            @if(isset($entry) && !empty($entry->student_files))
                            <small class="text-muted">
                                Current file(s): {{ implode(', ', array_map('basename', $entry->student_files)) }}
                                — upload new file(s) to replace them.
                            </small>
                            @else
                            <small class="text-muted">Upload your signed DTR sheet (PDF, image, or Word). Multiple files allowed.</small>
                            @endif
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> {{ isset($entry) ? 'Update DTR' : 'Submit DTR' }}
                            </button>
                            <a href="/student/dtr" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> DTR Guidelines</h5>
                </div>
                <div class="card-body">
                    <ul class="small mb-3">
                        <li><strong>Target hours:</strong> 720 hours total</li>
                        <li><strong>Week format:</strong> e.g., "Week 1", "January Week 2"</li>
                        <li><strong>Supervisor validation:</strong> Must be validated by your OJT supervisor</li>
                        <li>Submit DTR weekly for better tracking</li>
                    </ul>
                    <div class="alert alert-info mb-0" style="font-size:13px;">
                        Only <strong>approved</strong> hours count toward the 720-hour requirement.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
