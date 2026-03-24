@extends('layouts.student')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-clipboard-check"></i> Submit Supervisor Evaluation</h4>
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

                    <form action="/student/supervisor-eval" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="grade" class="form-label"><strong>Grade/Rating</strong> <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('student_supervisor_eval_grade') is-invalid @enderror" 
                                    id="grade" name="student_supervisor_eval_grade" 
                                    value="{{ old('student_supervisor_eval_grade') }}" 
                                    placeholder="e.g., A, 95%, Excellent" required>
                                @error('student_supervisor_eval_grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Enter the grade or rating you received from your supervisor.</small>
                            </div>
                            <div class="col-md-6">
                                <label for="file" class="form-label"><strong>Evaluation File</strong> <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('student_supervisor_eval_file') is-invalid @enderror" 
                                    id="file" name="student_supervisor_eval_file" required 
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('student_supervisor_eval_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-2">Max 5MB (PDF, DOC, DOCX, JPG, PNG)</small>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-info-circle"></i> Important Information</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Grade/Rating is required and will be automatically recorded</li>
                                        <li>The supervisor evaluation file must be signed by your supervisor</li>
                                        <li>Submission date will be automatically recorded</li>
                                        <li>Your supervisor/faculty will review and provide remarks</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-paper-plane"></i> Submit Evaluation
                                </button>
                                <a href="/student/supervisor-eval" class="btn btn-secondary btn-lg w-100 mt-2">
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
