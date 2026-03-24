@extends('layouts.admin')

@section('title', 'Manage Checklist Item')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-clipboard-list"></i> Manage {{ $item }}</h2>
    <p class="text-muted">
        Student: {{ $student->first_name }} {{ $student->last_name }} | Section: {{ $section->name }}
    </p>

    <div class="card mb-3">
        <div class="card-body">
            <p>This page shows submission details for <strong>{{ $item }}</strong> and allows faculty review.</p>
            <p>
                <strong>Submission encoded by student:</strong> {{ $entry->student_encoded_at?->format('Y-m-d H:i:s') ?? 'Not provided' }}<br>
                <strong>Student submission date:</strong> {{ $entry->student_submitted_at?->format('Y-m-d H:i:s') ?? 'Not submitted yet' }}
            </p>
            <p>
                <strong>Student remarks:</strong><br>
                {{ $entry->student_remarks ?? 'No remarks yet.' }}
            </p>
            <p>
                <strong>Current status:</strong>
                <span class="badge bg-{{ $entry->faculty_status == 'approved' ? 'success' : ($entry->faculty_status == 'declined' ? 'danger' : 'secondary') }}">{{ ucfirst($entry->faculty_status) }}</span>
            </p>
            <p>
                <strong>Faculty remarks:</strong><br>
                {{ $entry->faculty_remarks ?? 'Not set yet.' }}
            </p>

            @if(in_array($item, ['Medical Record', 'Receipt of OJT Kit', 'Waiver', 'Endorsement letter', 'MOA', 'DTR', 'Weekly report', 'Monthly appraisal', 'Supervisor evaluation', 'Certificate of completion']))
                <hr>
                <p><strong>Student enrollment encoded date:</strong> {{ $entry->student_encoded_at?->format('Y-m-d H:i:s') ?? 'Not encoded yet' }}</p>
                <p><strong>Student submission date:</strong> {{ $entry->student_submitted_at?->format('Y-m-d H:i:s') ?? 'Not submitted yet' }}</p>
                <p><strong>Submission status:</strong> {{ ucfirst($entry->student_submission_status ?? 'pending') }}</p>

                @if($item === 'Medical Record')
                    <p><strong>Clinic/Hospital name:</strong> {{ $entry->student_clinic_name ?? 'Not provided' }}</p>
                    <p><strong>Clinic/Hospital address:</strong> {{ $entry->student_clinic_address ?? 'Not provided' }}</p>
                    <p><strong>Uploaded medical files:</strong></p>
                @elseif($item === 'Receipt of OJT Kit')
                    <p><strong>Receipt paid date:</strong> {{ $entry->student_paid_date?->format('Y-m-d') ?? 'Not provided' }}</p>
                    <p><strong>Receipt reference number:</strong> {{ $entry->student_receipt_number ?? 'Not provided' }}</p>
                    <p><strong>Uploaded receipt file(s):</strong></p>
                @elseif($item === 'Waiver')
                    <p><strong>Guardian name:</strong> {{ $entry->student_guardian_name ?? 'Not provided' }}</p>
                    <p><strong>Guardian contact number:</strong> {{ $entry->student_guardian_contact ?? 'Not provided' }}</p>
                    <p><strong>Guardian email:</strong> {{ $entry->student_guardian_email ?? 'Not provided' }}</p>
                    <p><strong>Guardian social link:</strong> {{ $entry->student_guardian_social ?? 'Not provided' }}</p>
                    <p><strong>Uploaded waiver file(s):</strong></p>
                @elseif($item === 'Endorsement letter')
                    <p><strong>Endorsement date:</strong> {{ $entry->student_endorsement_date?->format('Y-m-d') ?? 'Not provided' }}</p>
                    <p><strong>Start date:</strong> {{ $entry->student_start_date?->format('Y-m-d') ?? 'Not provided' }}</p>
                    <p><strong>Supervisor signed by:</strong> {{ $entry->student_supervisor_signed_by ?? 'Not provided' }}</p>
                    <p><strong>Uploaded endorsement file(s):</strong></p>
                @elseif($item === 'MOA')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Note:</strong> MOA may have multiple signings and submissions (milestones) before final completion.</p>
                    </div>
                    <p><strong>Student remarks:</strong> {{ $entry->student_remarks ?? 'None' }}</p>
                    <p><strong>Uploaded MOA file(s) for this submission:</strong></p>
                @elseif($item === 'DTR')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Daily Time Record (DTR):</strong> Student submits weekly hours worked with supervisor validation.</p>
                    </div>
                    <p><strong>Week:</strong> {{ $entry->student_dtr_week ?? 'Not specified' }}</p>
                    <p><strong>Hours encoded:</strong> {{ $entry->student_dtr_hours ?? 'Not encoded' }} hours</p>
                    <p><strong>Validated by (Supervisor):</strong> {{ $entry->student_dtr_validated_by ?? 'Not specified' }}</p>
                    <p><strong>Total hours submitted so far:</strong> {{ $entry->student_dtr_total_hours ?? 0 }} hours</p>
                    <p><strong>Target hours (editable by faculty):</strong> <strong class="text-primary">{{ $entry->faculty_dtr_target_hours ?? 720 }}</strong> hours</p>
                    <p>
                        <strong class="text-{{ $entry->student_dtr_total_hours >= ($entry->faculty_dtr_target_hours ?? 720) ? 'success' : 'warning' }}">
                            Remaining hours: {{ max(0, ($entry->faculty_dtr_target_hours ?? 720) - ($entry->student_dtr_total_hours ?? 0)) }}
                        </strong>
                    </p>
                    <p><strong>Student remarks:</strong> {{ $entry->student_remarks ?? 'None' }}</p>
                @elseif($item === 'Weekly report')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Weekly Report:</strong> Student submits weekly task completed, supervisor feedback, and supporting documents.</p>
                    </div>
                    <p><strong>Week:</strong> {{ $entry->student_weekly_week ?? 'Not specified' }}</p>
                    <p><strong>Task Description:</strong></p>
                    <blockquote class="blockquote bg-light p-3 rounded">
                        {{ $entry->student_weekly_task_description ?? 'Not provided' }}
                    </blockquote>
                    <p><strong>Supervisor Feedback:</strong></p>
                    <blockquote class="blockquote bg-light p-3 rounded">
                        {{ $entry->student_weekly_supervisor_feedback ?? 'Not provided' }}
                    </blockquote>
                    <p><strong>Submitted on:</strong> {{ $entry->student_weekly_submitted_at?->format('M d, Y @ H:i') ?? 'Not submitted yet' }}</p>
                    <p><strong>Uploaded weekly report file(s):</strong></p>
                @elseif($item === 'Monthly appraisal')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Monthly Appraisal:</strong> Student performance evaluation submitted monthly with feedback, grade/rating, and supporting documents.</p>
                    </div>
                    <p><strong>Month:</strong> {{ $entry->student_appraisal_month ?? 'Not specified' }}</p>
                    <p><strong>Feedback (Optional):</strong></p>
                    @if($entry->student_appraisal_feedback)
                        <blockquote class="blockquote bg-light p-3 rounded">
                            {{ $entry->student_appraisal_feedback }}
                        </blockquote>
                    @else
                        <p class="text-muted">Not provided</p>
                    @endif
                    <p><strong>Grade/Rating (Optional):</strong> {{ $entry->student_appraisal_grade_rating ?? 'Not provided' }}</p>
                    <p><strong>Evaluated by:</strong> {{ $entry->student_appraisal_evaluated_by ?? 'Not specified' }}</p>
                    <p><strong>Submitted on:</strong> {{ $entry->student_appraisal_submitted_at?->format('M d, Y @ H:i') ?? 'Not submitted yet' }}</p>
                    <p><strong>Uploaded appraisal file:</strong></p>
                @elseif($item === 'Supervisor evaluation')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Supervisor Evaluation:</strong> Supervisor evaluation with required grade/rating and evaluation document.</p>
                    </div>
                    <p><strong>Grade/Rating:</strong> {{ $entry->student_supervisor_eval_grade ?? 'Not provided' }}</p>
                    <p><strong>Submitted on:</strong> {{ $entry->student_supervisor_eval_submitted_at?->format('M d, Y @ H:i') ?? 'Not submitted yet' }}</p>
                    <p><strong>Uploaded evaluation file:</strong></p>
                @elseif($item === 'Certificate of completion')
                    <div class="alert alert-info">
                        <p><i class="fas fa-info-circle"></i> <strong>Certificate of Completion:</strong> Certificate issued by the company after completing the OJT.</p>
                    </div>
                    <p><strong>Company:</strong> {{ $entry->student_coc_company ?? 'Not provided' }}</p>
                    <p><strong>Signed by:</strong> {{ $entry->student_coc_signed_by ?? 'Not provided' }}</p>
                    <p><strong>COC date issued:</strong> {{ $entry->student_coc_date_issued ? \Carbon\Carbon::parse($entry->student_coc_date_issued)->format('M d, Y') : 'Not provided' }}</p>
                    <p><strong>Receive / Revise date:</strong> {{ $entry->student_coc_receive_date ? \Carbon\Carbon::parse($entry->student_coc_receive_date)->format('M d, Y') : 'Not provided' }}</p>
                    <p><strong>Submitted on:</strong> {{ $entry->student_coc_submitted_at ? \Carbon\Carbon::parse($entry->student_coc_submitted_at)->format('M d, Y @ H:i') : 'Not submitted yet' }}</p>
                    <p><strong>Uploaded COC file:</strong></p>
                @endif

                @if($item === 'Supervisor evaluation')
                    @if($entry->student_supervisor_eval_file)
                        <ul>
                            <li><a href="/storage/{{ ltrim($entry->student_supervisor_eval_file, '/') }}" target="_blank">{{ basename($entry->student_supervisor_eval_file) }}</a></li>
                        </ul>
                    @else
                        <p class="text-muted">No file uploaded.</p>
                    @endif
                @elseif($item === 'Certificate of completion')
                    @if($entry->student_coc_file)
                        <ul>
                            <li><a href="/storage/{{ ltrim($entry->student_coc_file, '/') }}" target="_blank">{{ basename($entry->student_coc_file) }}</a></li>
                        </ul>
                    @else
                        <p class="text-muted">No file uploaded.</p>
                    @endif
                @elseif($entry->student_files && count($entry->student_files) > 0)
                    <ul>
                        @foreach($entry->student_files as $file)
                            <li><a href="/{{ ltrim($file, '/') }}" target="_blank">{{ basename($file) }}</a></li>
                        @endforeach
                    </ul>
                @elseif($entry->student_file)
                    <ul>
                        <li><a href="/{{ ltrim($entry->student_file, '/') }}" target="_blank">{{ basename($entry->student_file) }}</a></li>
                    </ul>
                @else
                    <p class="text-muted">No file uploaded.</p>
                @endif
            @else
                @if($entry->student_file)
                    <p><strong>Student file:</strong> <a href="/{{ ltrim($entry->student_file, '/') }}" target="_blank">View Upload</a></p>
                @else
                    <p class="text-muted"><em>No student file has been uploaded yet.</em></p>
                @endif
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="/faculty/section/{{ $section->id }}/students/{{ $student->id }}/checklist/{{ urlencode($item) }}">
        @csrf
        
        @if($item === 'DTR')
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Faculty Review - DTR</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Edit Target Hours (Total OJT requirement)</label>
                        <div class="input-group">
                            <input type="number" name="faculty_dtr_target_hours" class="form-control" step="0.01" 
                                value="{{ old('faculty_dtr_target_hours', $entry->faculty_dtr_target_hours ?? 720) }}" required>
                            <span class="input-group-text">hours</span>
                        </div>
                        <small class="form-text text-muted">Default is 720 hours. Adjust based on student requirements.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                        <small class="form-text text-muted">Approve: Valid DTR entry | Declined: Missing info or invalid hours</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty remarks (required if declined)</label>
                        <textarea name="faculty_remarks" class="form-control" rows="4" 
                            placeholder="e.g., 'Missing supervisor validation', 'Hours exceed daily limit', etc.">{{ old('faculty_remarks', $entry->faculty_remarks) }}</textarea>
                    </div>
                </div>
            </div>
        @elseif($item === 'Weekly report')
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Faculty Review - Weekly Report</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Faculty status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                        <small class="form-text text-muted">Approve: Quality report | Declined: Incomplete or needs revision</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty remarks and feedback</label>
                        <textarea name="faculty_weekly_remarks" class="form-control" rows="4" 
                            placeholder="Provide feedback on the weekly report, task completion, or areas for improvement.">{{ old('faculty_weekly_remarks', $entry->faculty_weekly_remarks) }}</textarea>
                        <small class="form-text text-muted">Required if declined. Recommended for all submissions to guide student improvement.</small>
                    </div>
                </div>
            </div>
        @elseif($item === 'Monthly appraisal')
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Faculty Review - Monthly Appraisal</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Faculty status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                        <small class="form-text text-muted">Approve: Acceptable appraisal | Declined: Incomplete or needs revision</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty remarks and feedback</label>
                        <textarea name="faculty_appraisal_remarks" class="form-control" rows="4" 
                            placeholder="Provide feedback on the appraisal submission, overall performance assessment, or areas for improvement.">{{ old('faculty_appraisal_remarks', $entry->faculty_appraisal_remarks) }}</textarea>
                        <small class="form-text text-muted">Required if declined. Provide constructive feedback for the student's development.</small>
                    </div>
                </div>
            </div>
        @elseif($item === 'Supervisor evaluation')
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Faculty Review - Supervisor Evaluation</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Faculty status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                        <small class="form-text text-muted">Approve: Acceptable evaluation | Declined: Incomplete or needs revision</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty remarks and feedback</label>
                        <textarea name="faculty_supervisor_eval_remarks" class="form-control" rows="4"
                            placeholder="Provide feedback on the supervisor evaluation or results.">{{ old('faculty_supervisor_eval_remarks', $entry->faculty_supervisor_eval_remarks) }}</textarea>
                        <small class="form-text text-muted">Required if declined. Provide constructive feedback for student improvement.</small>
                    </div>
                </div>
            </div>
        @elseif($item === 'Certificate of completion')
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Faculty Review - Certificate of Completion</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Faculty status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                        <small class="form-text text-muted">Approve: Valid COC | Declined: Incomplete or needs revision</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Faculty remarks and feedback</label>
                        <textarea name="faculty_coc_remarks" class="form-control" rows="4"
                            placeholder="Provide feedback on the certificate of completion.">{{ old('faculty_coc_remarks', $entry->faculty_coc_remarks) }}</textarea>
                        <small class="form-text text-muted">Required if declined. Provide reason or additional notes.</small>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-3">
                <label class="form-label">Faculty status</label>
                <select name="faculty_status" class="form-select" required>
                    <option value="pending" {{ $entry->faculty_status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $entry->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="declined" {{ $entry->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Faculty remarks (required if declined)</label>
                <textarea name="faculty_remarks" class="form-control" rows="4" placeholder="Add a reason or additional notes">{{ old('faculty_remarks', $entry->faculty_remarks) }}</textarea>
            </div>
        @endif

        <div class="mb-3">
            <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Save Review</button>
            <a href="/faculty/section/{{ $section->id }}/students/{{ $student->id }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to student list</a>
        </div>
    </form>
</div>
@endsection