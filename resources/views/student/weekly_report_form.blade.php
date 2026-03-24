@extends('layouts.student')

@section('title', isset($entry) ? 'Edit Weekly Report' : 'Submit Weekly Report')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-{{ isset($entry) ? 'edit' : 'plus-circle' }}"></i>
            {{ isset($entry) ? 'Edit Weekly Report — ' . $entry->student_weekly_week : 'Submit Weekly Report' }}
        </h2>
        <a href="/student/weekly-report" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Reports</a>
    </div>

    @if(isset($entry) && $entry->faculty_status === 'declined')
    <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i> <strong>Revision Needed.</strong>
        @if($entry->faculty_weekly_remarks) Reason: <em>{{ $entry->faculty_weekly_remarks }}</em>@endif
        Please correct and resubmit below.
    </div>
    @endif

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">{{ isset($entry) ? 'Update Weekly Report' : 'Weekly Report Submission' }}</h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST"
                          action="{{ isset($entry) ? '/student/weekly-report/' . $entry->id : '/student/weekly-report' }}"
                          enctype="multipart/form-data">
                        @csrf
                        @if(isset($entry)) @method('PUT') @endif

                        <div class="mb-3">
                            <label for="student_weekly_week" class="form-label">Week <span class="text-danger">*</span></label>
                            <input type="text" id="student_weekly_week" name="student_weekly_week"
                                class="form-control @error('student_weekly_week') is-invalid @enderror"
                                placeholder="e.g., Week 1, Week 2, March Week 3..."
                                value="{{ old('student_weekly_week', $entry->student_weekly_week ?? '') }}" required>
                            <small class="form-text text-muted">Specify which week you're reporting on</small>
                            @error('student_weekly_week')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_task_description" class="form-label">Task Description <span class="text-danger">*</span></label>
                            <textarea id="student_weekly_task_description" name="student_weekly_task_description"
                                class="form-control @error('student_weekly_task_description') is-invalid @enderror"
                                rows="5" placeholder="Describe the tasks you completed this week...">{{ old('student_weekly_task_description', $entry->student_weekly_task_description ?? '') }}</textarea>
                            <small class="form-text text-muted">Minimum 10 characters.</small>
                            @error('student_weekly_task_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_supervisor_feedback" class="form-label">Supervisor Feedback/Remarks <span class="text-danger">*</span></label>
                            <textarea id="student_weekly_supervisor_feedback" name="student_weekly_supervisor_feedback"
                                class="form-control @error('student_weekly_supervisor_feedback') is-invalid @enderror"
                                rows="4" placeholder="What did your supervisor say about your performance?">{{ old('student_weekly_supervisor_feedback', $entry->student_weekly_supervisor_feedback ?? '') }}</textarea>
                            <small class="form-text text-muted">Minimum 5 characters.</small>
                            @error('student_weekly_supervisor_feedback')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_files" class="form-label">Upload Supporting Documents (Optional)</label>
                            <input type="file" id="student_weekly_files" name="student_weekly_files[]"
                                class="form-control @error('student_weekly_files.*') is-invalid @enderror"
                                multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx">
                            @if(isset($entry) && !empty($entry->student_weekly_files))
                            <small class="text-muted">
                                Current file(s): {{ implode(', ', array_map('basename', $entry->student_weekly_files)) }}
                                — upload new file(s) to replace them.
                            </small>
                            @else
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Multiple uploads allowed. Max 5MB per file.
                                Supported: PDF, DOC, DOCX, JPG, PNG, XLSX
                            </small>
                            @endif
                            @error('student_weekly_files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="filePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> {{ isset($entry) ? 'Update Report' : 'Submit Report' }}
                            </button>
                            <a href="/student/weekly-report" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Report Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>What to Include:</h6>
                    <ul class="small">
                        <li><strong>Tasks Completed:</strong> Daily activities, projects, deliverables</li>
                        <li><strong>Skills Learned:</strong> New techniques, tools, knowledge gained</li>
                        <li><strong>Challenges:</strong> Problems encountered and how you solved them</li>
                        <li><strong>Supervisor Input:</strong> Their evaluation and feedback</li>
                    </ul>

                    <h6 class="mt-3">Best Practices:</h6>
                    <ul class="small">
                        <li>Submit weekly for better tracking</li>
                        <li>Be honest and detailed in your descriptions</li>
                        <li>Include supporting documents (reports, certificates, photos)</li>
                        <li>Use clear, professional language</li>
                    </ul>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Note:</strong> Faculty will review your report and provide feedback.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('student_weekly_files').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        if (this.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.className = 'alert alert-info mb-0';
            fileList.innerHTML = '<strong>Selected Files:</strong><ul class="mb-0 mt-2">';
            for (let file of this.files) {
                fileList.innerHTML += `<li>${file.name} (${(file.size/1024).toFixed(2)} KB)</li>`;
            }
            fileList.innerHTML += '</ul>';
            preview.appendChild(fileList);
        }
    });
</script>
@endsection
