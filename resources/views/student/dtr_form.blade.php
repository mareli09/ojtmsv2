@extends('layouts.admin')

@section('title', 'Submit Daily Time Record')

@section('sidebar')
    <a href="/student/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/student/dtr" class="nav-link active"><i class="fas fa-clock"></i> Daily Time Record</a>
    <a href="/student/weekly-report" class="nav-link"><i class="fas fa-file-alt"></i> Weekly Reports</a>
    <a href="/student/monthly-appraisal" class="nav-link"><i class="fas fa-star"></i> Monthly Appraisal</a>
    <a href="/student/supervisor-eval" class="nav-link"><i class="fas fa-user-check"></i> Supervisor Evaluation</a>
    <a href="/student/coc" class="nav-link"><i class="fas fa-certificate"></i> Certificate of Completion</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-plus-circle"></i> Submit Daily Time Record</h2>
        <a href="/student/dtr" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to DTR</a>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Weekly DTR Submission</h5>
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

                    <form method="POST" action="/student/dtr">
                        @csrf

                        <div class="mb-3">
                            <label for="student_dtr_week" class="form-label">Week <span class="text-danger">*</span></label>
                            <input type="text" id="student_dtr_week" name="student_dtr_week" class="form-control @error('student_dtr_week') is-invalid @enderror" 
                                placeholder="e.g., Week 1, Week 2, Week 3..." value="{{ old('student_dtr_week') }}" required>
                            <small class="form-text text-muted">Specify which week you're submitting for (e.g., Week 1, Week 2 of March, etc.)</small>
                            @error('student_dtr_week')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_dtr_hours" class="form-label">Hours Worked <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <input type="number" id="student_dtr_hours" name="student_dtr_hours" class="form-control @error('student_dtr_hours') is-invalid @enderror" 
                                    placeholder="0.5" step="0.5" min="0" max="60" value="{{ old('student_dtr_hours') }}" required>
                                <span class="input-group-text">hours</span>
                                @error('student_dtr_hours')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Enter the total hours worked for this week (max 60 hours per submission)</small>
                        </div>

                        <div class="mb-3">
                            <label for="student_dtr_validated_by" class="form-label">Validated by (Supervisor Name) <span class="text-danger">*</span></label>
                            <input type="text" id="student_dtr_validated_by" name="student_dtr_validated_by" class="form-control @error('student_dtr_validated_by') is-invalid @enderror" 
                                placeholder="Supervisor's full name" value="{{ old('student_dtr_validated_by') }}" required>
                            <small class="form-text text-muted">Name of the supervisor who validated your hours</small>
                            @error('student_dtr_validated_by')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="student_remarks" class="form-label">Remarks (Optional)</label>
                            <textarea id="student_remarks" name="student_remarks" class="form-control" rows="4" 
                                placeholder="Any additional notes or context about this week's work...">{{ old('student_remarks') }}</textarea>
                            <small class="form-text text-muted">e.g., 'Worked on project X', 'Training completed', etc.</small>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane"></i> Submit DTR</button>
                            <a href="/student/dtr" class="btn btn-secondary"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card bg-light">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="fas fa-lightbulb"></i> DTR Guidelines</h5>
                </div>
                <div class="card-body">
                    <h6>Requirements:</h6>
                    <ul class="small">
                        <li><strong>Target hours:</strong> 720 hours total</li>
                        <li><strong>Week format:</strong> Use clear week identifiers (e.g., "Week 1", "January Week 2")</li>
                        <li><strong>Supervisor validation:</strong> Must be signed/validated by your OJT supervisor</li>
                        <li><strong>Accuracy:</strong> Ensure hours reflect actual time worked</li>
                    </ul>

                    <h6 class="mt-3">Tips:</h6>
                    <ul class="small">
                        <li>Submit DTR weekly for better tracking</li>
                        <li>Keep supervisor name consistent</li>
                        <li>Include relevant details in remarks</li>
                        <li>Faculty will review and approve/decline</li>
                    </ul>

                    <div class="alert alert-info mt-3" role="alert">
                        <strong>Note:</strong> Only <strong>approved</strong> hours count toward your 720-hour requirement. Faculty may request revisions if hours don't match records.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
