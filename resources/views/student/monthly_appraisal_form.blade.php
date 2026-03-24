@extends('layouts.admin')

@section('title', 'Submit Monthly Appraisal')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link active"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle"></i> Submit Monthly Appraisal</h2>
        <a href="/student/monthly-appraisal" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Appraisals</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Monthly Appraisal Submission</h5>
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

                    <form method="POST" action="/student/monthly-appraisal" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="student_appraisal_month" class="form-label">Month <span class="text-danger">*</span></label>
                            <input type="text" id="student_appraisal_month" name="student_appraisal_month" class="form-control @error('student_appraisal_month') is-invalid @enderror" 
                                placeholder="e.g., March, January 2026, March 2026..." value="{{ old('student_appraisal_month') }}" required>
                            <small class="form-text text-muted">Specify which month/period this appraisal covers</small>
                            @error('student_appraisal_month')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_appraisal_feedback" class="form-label">Your Feedback/Comments (Optional)</label>
                            <textarea id="student_appraisal_feedback" name="student_appraisal_feedback" class="form-control @error('student_appraisal_feedback') is-invalid @enderror" 
                                rows="4" placeholder="Any personal thoughts, challenges faced, skills developed, or self-assessment...">{{ old('student_appraisal_feedback') }}</textarea>
                            <small class="form-text text-muted">Share your self-assessment and feedback on your performance this month.</small>
                            @error('student_appraisal_feedback')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_appraisal_grade_rating" class="form-label">Grade or Rating (Optional)</label>
                            <input type="text" id="student_appraisal_grade_rating" name="student_appraisal_grade_rating" class="form-control @error('student_appraisal_grade_rating') is-invalid @enderror" 
                                placeholder="e.g., A, B+, 85%, Excellent, Good, Satisfactory..." value="{{ old('student_appraisal_grade_rating') }}">
                            <small class="form-text text-muted">Enter your grade/rating for this period (e.g., A, B+, 85%, or descriptive term)</small>
                            @error('student_appraisal_grade_rating')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_appraisal_evaluated_by" class="form-label">Evaluated by (Supervisor Name) (Optional)</label>
                            <input type="text" id="student_appraisal_evaluated_by" name="student_appraisal_evaluated_by" class="form-control @error('student_appraisal_evaluated_by') is-invalid @enderror" 
                                placeholder="Your supervisor or evaluator's name" value="{{ old('student_appraisal_evaluated_by') }}">
                            <small class="form-text text-muted">Name of the person who evaluated your performance</small>
                            @error('student_appraisal_evaluated_by')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_appraisal_file" class="form-label">Upload Appraisal Document (Optional)</label>
                            <input type="file" id="student_appraisal_file" name="student_appraisal_file" class="form-control @error('student_appraisal_file') is-invalid @enderror" 
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.xlsx">
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Single file upload. Max 5MB. 
                                Supported: PDF, DOC, DOCX, JPG, PNG, XLSX
                            </small>
                            @error('student_appraisal_file')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <div id="filePreview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit Appraisal</button>
                            <a href="/student/monthly-appraisal" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Appraisal Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Required Fields:</h6>
                    <ul class="small">
                        <li><strong>Month:</strong> Period covered by the appraisal</li>
                    </ul>

                    <h6 class="mt-3">Optional Fields:</h6>
                    <ul class="small">
                        <li><strong>Feedback:</strong> Your self-assessment</li>
                        <li><strong>Grade/Rating:</strong> Performance rating</li>
                        <li><strong>Evaluated by:</strong> Evaluator's name</li>
                        <li><strong>Document:</strong> Supporting appraisal form</li>
                    </ul>

                    <h6 class="mt-3">What to Include:</h6>
                    <ul class="small">
                        <li>Accomplishments and achievements</li>
                        <li>Challenges and how you overcame them</li>
                        <li>Skills acquired or improved</li>
                        <li>Goals for next month</li>
                        <li>Supervisor's formal evaluation (if available)</li>
                    </ul>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Note:</strong> Faculty will review your appraisal and provide feedback. Most fields are optional, allowing flexibility in submission format.
                    </div>

                    <h6 class="mt-3">Rating Scale Examples:</h6>
                    <ul class="small">
                        <li>Letter grades: A, B+, B, C+, C</li>
                        <li>Percentages: 90%, 85%, 75%</li>
                        <li>Descriptive: Excellent, Good, Satisfactory, Needs Improvement</li>
                        <li>Numeric: 5/5, 4/5, 3/5</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // File preview
    document.getElementById('student_appraisal_file').addEventListener('change', function(e) {
        const preview = document.getElementById('filePreview');
        preview.innerHTML = '';
        
        if (this.files.length > 0) {
            const file = this.files[0];
            const size = (file.size / 1024).toFixed(2);
            const fileInfo = document.createElement('div');
            fileInfo.className = 'alert alert-info mb-0';
            fileInfo.innerHTML = `<strong>Selected File:</strong><br><i class="fas fa-file"></i> ${file.name} (${size} KB)`;
            preview.appendChild(fileInfo);
        }
    });
</script>
@endsection
