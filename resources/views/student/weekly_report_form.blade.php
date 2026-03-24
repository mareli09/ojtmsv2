@extends('layouts.admin')

@section('title', 'Submit Weekly Report')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link active"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle"></i> Submit Weekly Report</h2>
        <a href="/student/weekly-report" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Reports</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Weekly Report Submission</h5>
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

                    <form method="POST" action="/student/weekly-report" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="student_weekly_week" class="form-label">Week <span class="text-danger">*</span></label>
                            <input type="text" id="student_weekly_week" name="student_weekly_week" class="form-control @error('student_weekly_week') is-invalid @enderror" 
                                placeholder="e.g., Week 1, Week 2, March Week 3..." value="{{ old('student_weekly_week') }}" required>
                            <small class="form-text text-muted">Specify which week you're reporting on</small>
                            @error('student_weekly_week')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_task_description" class="form-label">Task Description <span class="text-danger">*</span></label>
                            <textarea id="student_weekly_task_description" name="student_weekly_task_description" class="form-control @error('student_weekly_task_description') is-invalid @enderror" 
                                rows="5" placeholder="Describe the tasks you completed this week, projects worked on, skills gained, etc.">{{ old('student_weekly_task_description') }}</textarea>
                            <small class="form-text text-muted">Minimum 10 characters. Be detailed about your accomplishments and learning.</small>
                            @error('student_weekly_task_description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_supervisor_feedback" class="form-label">Supervisor Feedback/Remarks <span class="text-danger">*</span></label>
                            <textarea id="student_weekly_supervisor_feedback" name="student_weekly_supervisor_feedback" class="form-control @error('student_weekly_supervisor_feedback') is-invalid @enderror" 
                                rows="4" placeholder="What did your supervisor say about your performance? Any improvements needed?">{{ old('student_weekly_supervisor_feedback') }}</textarea>
                            <small class="form-text text-muted">Minimum 5 characters. Include feedback from your supervisor.</small>
                            @error('student_weekly_supervisor_feedback')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_weekly_files" class="form-label">Upload Supporting Documents (Optional)</label>
                            <input type="file" id="student_weekly_files" name="student_weekly_files[]" class="form-control @error('student_weekly_files.*') is-invalid @enderror" 
                                multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Multiple uploads allowed. Max 5MB per file. 
                                Supported: PDF, DOC, DOCX, JPG, PNG, XLSX
                            </small>
                            @error('student_weekly_files.*')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="filePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit Report</button>
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
                        <li>Attach evidence of work completed if available</li>
                    </ul>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Note:</strong> Faculty will review your report and provide feedback. Respond to any feedback for continuous improvement.
                    </div>

                    <h6 class="mt-3">File Upload Tips:</h6>
                    <ul class="small">
                        <li>Allowed: PDF, Word, Excel, images</li>
                        <li>Max 5MB per file</li>
                        <li>Upload screenshots or photos of work</li>
                        <li>Include weekly output samples if applicable</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // File preview
    document.getElementById('student_weekly_files').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        
        if (this.files.length > 0) {
            const fileList = document.createElement('div');
            fileList.className = 'alert alert-info mb-0';
            fileList.innerHTML = '<strong>Selected Files:</strong><ul class="mb-0 mt-2">';
            
            for (let file of this.files) {
                const size = (file.size / 1024).toFixed(2);
                fileList.innerHTML += `<li>${file.name} (${size} KB)</li>`;
            }
            
            fileList.innerHTML += '</ul>';
            preview.appendChild(fileList);
        }
    });
</script>
@endsection
