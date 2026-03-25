@extends('layouts.admin')

@section('title', 'Manage Checklist Item')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
@php
    $recurringItems = ['DTR', 'Weekly report', 'Monthly appraisal', 'Supervisor evaluation', 'Certificate of completion'];
    $isRecurring = in_array($item, $recurringItems);
@endphp

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2><i class="fas fa-clipboard-list"></i> {{ $item }}</h2>
            <p class="text-muted mb-0">
                Student: <strong>{{ $student->first_name }} {{ $student->last_name }}</strong> |
                Section: <strong>{{ $section->name }}</strong>
            </p>
        </div>
        <a href="/faculty/section/{{ $section->id }}/students/{{ $student->id }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Checklist
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- ===================== RECURRING ITEMS (DTR, Weekly Report, etc.) ===================== --}}
    @if($isRecurring)

        @if($entries->isEmpty())
        <div class="alert alert-info">
            <i class="fas fa-info-circle"></i> No submissions yet for <strong>{{ $item }}</strong>.
        </div>
        @else

        {{-- DTR summary bar --}}
        @if($item === 'DTR')
        @php
            $approvedHours = $entries->where('faculty_status', 'approved')->sum('student_dtr_hours');
            $targetHours   = $entries->first()?->faculty_dtr_target_hours ?? 720;
            $pct           = $targetHours > 0 ? min(100, ($approvedHours / $targetHours) * 100) : 0;
        @endphp
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-md-3 text-center">
                        <div class="text-muted small">Total Submissions</div>
                        <div class="fs-4 fw-bold text-info">{{ $entries->count() }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-muted small">Approved Hours</div>
                        <div class="fs-4 fw-bold text-success">{{ $approvedHours }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-muted small">Target Hours</div>
                        <div class="fs-4 fw-bold text-primary">{{ $targetHours }}</div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div class="text-muted small">Remaining</div>
                        <div class="fs-4 fw-bold text-{{ max(0, $targetHours - $approvedHours) > 0 ? 'warning' : 'success' }}">
                            {{ max(0, $targetHours - $approvedHours) }}
                        </div>
                    </div>
                </div>
                <div class="progress" style="height:18px;">
                    <div class="progress-bar bg-success" style="width:{{ $pct }}%">{{ number_format($pct, 1) }}%</div>
                </div>
            </div>
        </div>
        @endif

        {{-- Submissions table --}}
        <div class="card">
            <div class="card-header">
                All {{ $item }} Submissions ({{ $entries->count() }})
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            @if($item === 'DTR')
                                <th>Week</th><th>Hours</th><th>Validated by</th>
                            @elseif($item === 'Weekly report')
                                <th>Week</th><th>Task Summary</th>
                            @elseif($item === 'Monthly appraisal')
                                <th>Month</th><th>Grade/Rating</th>
                            @elseif($item === 'Supervisor evaluation')
                                <th>Grade/Rating</th>
                            @elseif($item === 'Certificate of completion')
                                <th>Company</th><th>Date Issued</th>
                            @endif
                            <th>Submitted</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $i => $e)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            @if($item === 'DTR')
                                <td><strong>{{ $e->student_dtr_week ?? '—' }}</strong></td>
                                <td><span class="badge bg-info">{{ $e->student_dtr_hours ?? 0 }} hrs</span></td>
                                <td>{{ $e->student_dtr_validated_by ?? '—' }}</td>
                            @elseif($item === 'Weekly report')
                                <td><strong>{{ $e->student_weekly_week ?? '—' }}</strong></td>
                                <td><small>{{ Str::limit($e->student_weekly_task_description ?? '—', 40) }}</small></td>
                            @elseif($item === 'Monthly appraisal')
                                <td><strong>{{ $e->student_appraisal_month ?? '—' }}</strong></td>
                                <td>{{ $e->student_appraisal_grade_rating ?? '—' }}</td>
                            @elseif($item === 'Supervisor evaluation')
                                <td>{{ $e->student_supervisor_eval_grade ?? '—' }}</td>
                            @elseif($item === 'Certificate of completion')
                                <td>{{ $e->student_coc_company ?? '—' }}</td>
                                <td>{{ $e->student_coc_date_issued ? \Carbon\Carbon::parse($e->student_coc_date_issued)->format('M d, Y') : '—' }}</td>
                            @endif
                            <td>
                                @if($item === 'Weekly report')
                                    {{ $e->student_weekly_submitted_at?->format('M d, Y') ?? '—' }}
                                @elseif($item === 'Monthly appraisal')
                                    {{ $e->student_appraisal_submitted_at?->format('M d, Y') ?? '—' }}
                                @elseif($item === 'Supervisor evaluation')
                                    {{ $e->student_supervisor_eval_submitted_at?->format('M d, Y') ?? '—' }}
                                @elseif($item === 'Certificate of completion')
                                    {{ $e->student_coc_submitted_at?->format('M d, Y') ?? '—' }}
                                @else
                                    {{ $e->student_submitted_at?->format('M d, Y') ?? '—' }}
                                @endif
                            </td>
                            <td>
                                @php $sc = $e->faculty_status === 'approved' ? 'success' : ($e->faculty_status === 'declined' ? 'danger' : 'warning'); @endphp
                                <span class="badge bg-{{ $sc }}">{{ ucfirst($e->faculty_status ?? 'pending') }}</span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#reviewModal{{ $e->id }}">
                                    <i class="fas fa-edit"></i> Review
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    {{-- ===================== ONE-TIME ITEMS ===================== --}}
    @else

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Submitted:</strong> {{ $entry?->student_submitted_at?->format('M d, Y g:i A') ?? 'Not submitted yet' }}</p>
            <p><strong>Status:</strong>
                <span class="badge bg-{{ ($entry?->faculty_status ?? '') == 'approved' ? 'success' : (($entry?->faculty_status ?? '') == 'declined' ? 'danger' : 'secondary') }}">
                    {{ ucfirst($entry?->faculty_status ?? 'pending') }}
                </span>
            </p>
            @if($entry?->student_remarks)
            <p><strong>Student remarks:</strong> {{ $entry->student_remarks }}</p>
            @endif
            @if($entry?->faculty_remarks)
            <p><strong>Faculty remarks:</strong> {{ $entry->faculty_remarks }}</p>
            @endif

            <hr>

            @if($item === 'Registration card')
                <p><strong>Uploaded file:</strong></p>
                @if($entry?->student_file)
                    <a href="{{ route('file.download', ['path' => $entry->student_file]) }}" target="_blank">{{ basename($entry->student_file) }}</a>
                @else <p class="text-muted">No file uploaded.</p> @endif

            @elseif($item === 'Medical Record')
                <p><strong>Clinic/Hospital:</strong> {{ $entry?->student_clinic_name ?? '—' }}, {{ $entry?->student_clinic_address ?? '—' }}</p>
                <p><strong>Files:</strong></p>
                @if($entry?->student_files && count($entry->student_files) > 0)
                    <ul>@foreach($entry->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @else <p class="text-muted">No files uploaded.</p> @endif

            @elseif($item === 'Receipt of OJT Kit')
                <p><strong>Date Paid:</strong> {{ $entry?->student_paid_date?->format('M d, Y') ?? '—' }}</p>
                <p><strong>OR/Reference Number:</strong> {{ $entry?->student_receipt_number ?? '—' }}</p>
                <p><strong>Files:</strong></p>
                @if($entry?->student_files && count($entry->student_files) > 0)
                    <ul>@foreach($entry->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @else <p class="text-muted">No files uploaded.</p> @endif

            @elseif($item === 'Waiver')
                <p><strong>Guardian:</strong> {{ $entry?->student_guardian_name ?? '—' }} | {{ $entry?->student_guardian_contact ?? '—' }}</p>
                @if($entry?->student_guardian_email)<p><strong>Email:</strong> {{ $entry->student_guardian_email }}</p>@endif
                <p><strong>Files:</strong></p>
                @if($entry?->student_files && count($entry->student_files) > 0)
                    <ul>@foreach($entry->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @else <p class="text-muted">No files uploaded.</p> @endif

            @elseif($item === 'Endorsement letter')
                <p><strong>Endorsement Date:</strong> {{ $entry?->student_endorsement_date?->format('M d, Y') ?? '—' }}</p>
                <p><strong>OJT Start Date:</strong> {{ $entry?->student_start_date?->format('M d, Y') ?? '—' }}</p>
                <p><strong>Signed by:</strong> {{ $entry?->student_supervisor_signed_by ?? '—' }}</p>
                <p><strong>Files:</strong></p>
                @if($entry?->student_files && count($entry->student_files) > 0)
                    <ul>@foreach($entry->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @else <p class="text-muted">No files uploaded.</p> @endif

            @elseif($item === 'MOA')
                <p><strong>Student remarks:</strong> {{ $entry?->student_remarks ?? 'None' }}</p>
                <p><strong>Files:</strong></p>
                @if($entry?->student_files && count($entry->student_files) > 0)
                    <ul>@foreach($entry->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @else <p class="text-muted">No files uploaded.</p> @endif

            @else
                @if($entry?->student_file)
                    <p><a href="{{ route('file.download', ['path' => $entry->student_file]) }}" target="_blank">View Upload</a></p>
                @else <p class="text-muted">No file uploaded.</p> @endif
            @endif
        </div>
    </div>

    {{-- One-time item review form --}}
    <form method="POST" action="/faculty/section/{{ $section->id }}/students/{{ $student->id }}/checklist/{{ urlencode($item) }}">
        @csrf
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Faculty Review</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="faculty_status" class="form-select" required>
                        <option value="pending"  {{ ($entry?->faculty_status ?? '') == 'pending'  ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ ($entry?->faculty_status ?? '') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="declined" {{ ($entry?->faculty_status ?? '') == 'declined' ? 'selected' : '' }}>Declined</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Remarks (required if declined)</label>
                    <textarea name="faculty_remarks" class="form-control" rows="4"
                        placeholder="Add feedback or reason for declining...">{{ old('faculty_remarks', $entry?->faculty_remarks) }}</textarea>
                    @error('faculty_remarks')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Save Review</button>
                <a href="/faculty/section/{{ $section->id }}/students/{{ $student->id }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>

    @endif {{-- end one-time items --}}
</div>

{{-- Per-entry review modals for recurring items (outside container) --}}
@if($isRecurring)
@foreach($entries as $e)
<div class="modal fade" id="reviewModal{{ $e->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Review {{ $item }}
                    @if($item === 'DTR') — {{ $e->student_dtr_week }}
                    @elseif($item === 'Weekly report') — {{ $e->student_weekly_week }}
                    @elseif($item === 'Monthly appraisal') — {{ $e->student_appraisal_month }}
                    @endif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                {{-- Entry details --}}
                @if($item === 'DTR')
                <div class="row mb-2">
                    <div class="col-md-4"><strong>Week:</strong> {{ $e->student_dtr_week ?? '—' }}</div>
                    <div class="col-md-4"><strong>Hours:</strong> {{ $e->student_dtr_hours ?? 0 }} hrs</div>
                    <div class="col-md-4"><strong>Validated by:</strong> {{ $e->student_dtr_validated_by ?? '—' }}</div>
                </div>
                @if($e->student_remarks)<p><strong>Remarks:</strong> {{ $e->student_remarks }}</p>@endif
                @if(!empty($e->student_files))
                <p><strong>Files:</strong></p>
                <ul>@foreach($e->student_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @endif
                <p><strong>Submitted:</strong> {{ $e->student_submitted_at?->format('M d, Y g:i A') ?? '—' }}</p>

                @elseif($item === 'Weekly report')
                <p><strong>Week:</strong> {{ $e->student_weekly_week ?? '—' }}</p>
                <p><strong>Submitted:</strong> {{ $e->student_weekly_submitted_at?->format('M d, Y g:i A') ?? '—' }}</p>
                <h6>Task Description:</h6>
                <div class="bg-light p-2 rounded mb-2">{{ $e->student_weekly_task_description ?? 'Not provided' }}</div>
                <h6>Supervisor Feedback:</h6>
                <div class="bg-light p-2 rounded mb-2">{{ $e->student_weekly_supervisor_feedback ?? 'Not provided' }}</div>
                @if(!empty($e->student_weekly_files))
                <p><strong>Files:</strong></p>
                <ul>@foreach($e->student_weekly_files as $f)<li><a href="{{ route('file.download', ['path' => $f]) }}" target="_blank">{{ basename($f) }}</a></li>@endforeach</ul>
                @endif

                @elseif($item === 'Monthly appraisal')
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Month:</strong> {{ $e->student_appraisal_month ?? '—' }}</div>
                    <div class="col-md-6"><strong>Grade/Rating:</strong> {{ $e->student_appraisal_grade_rating ?? '—' }}</div>
                </div>
                <p><strong>Evaluated by:</strong> {{ $e->student_appraisal_evaluated_by ?? '—' }}</p>
                @if($e->student_appraisal_feedback)<div class="bg-light p-2 rounded mb-2">{{ $e->student_appraisal_feedback }}</div>@endif
                @if($e->student_appraisal_file)<p><strong>File:</strong> <a href="{{ route('file.download', ['path' => $e->student_appraisal_file]) }}" target="_blank">{{ basename($e->student_appraisal_file) }}</a></p>@endif

                @elseif($item === 'Supervisor evaluation')
                <p><strong>Grade/Rating:</strong> {{ $e->student_supervisor_eval_grade ?? '—' }}</p>
                <p><strong>Submitted:</strong> {{ $e->student_supervisor_eval_submitted_at?->format('M d, Y g:i A') ?? '—' }}</p>
                @if($e->student_supervisor_eval_file)<p><strong>File:</strong> <a href="{{ route('file.download', ['path' => $e->student_supervisor_eval_file]) }}" target="_blank">{{ basename($e->student_supervisor_eval_file) }}</a></p>@endif

                @elseif($item === 'Certificate of completion')
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Company:</strong> {{ $e->student_coc_company ?? '—' }}</div>
                    <div class="col-md-6"><strong>Signed by:</strong> {{ $e->student_coc_signed_by ?? '—' }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-md-6"><strong>Date Issued:</strong> {{ $e->student_coc_date_issued ? \Carbon\Carbon::parse($e->student_coc_date_issued)->format('M d, Y') : '—' }}</div>
                    <div class="col-md-6"><strong>Receive Date:</strong> {{ $e->student_coc_receive_date ? \Carbon\Carbon::parse($e->student_coc_receive_date)->format('M d, Y') : '—' }}</div>
                </div>
                @if($e->student_coc_file)<p><strong>File:</strong> <a href="{{ route('file.download', ['path' => $e->student_coc_file]) }}" target="_blank">{{ basename($e->student_coc_file) }}</a></p>@endif
                @endif

                <hr>

                {{-- Review form --}}
                <form method="POST" action="/faculty/section/{{ $section->id }}/students/{{ $student->id }}/checklist/{{ urlencode($item) }}/{{ $e->id }}">
                    @csrf
                    @if($item === 'DTR')
                    <div class="mb-3">
                        <label class="form-label">Target Hours</label>
                        <div class="input-group">
                            <input type="number" name="faculty_dtr_target_hours" class="form-control" step="0.5"
                                value="{{ $e->faculty_dtr_target_hours ?? 720 }}">
                            <span class="input-group-text">hours</span>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="faculty_status" class="form-select" required>
                            <option value="pending"  {{ $e->faculty_status == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $e->faculty_status == 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="declined" {{ $e->faculty_status == 'declined' ? 'selected' : '' }}>Declined</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            @if($item === 'Weekly report') Faculty Feedback
                            @elseif($item === 'Monthly appraisal') Faculty Feedback
                            @elseif($item === 'Supervisor evaluation') Faculty Remarks
                            @elseif($item === 'Certificate of completion') Faculty Remarks
                            @else Faculty Remarks
                            @endif
                            <small class="text-muted">(required if declined)</small>
                        </label>
                        @if($item === 'Weekly report')
                        <textarea name="faculty_weekly_remarks" class="form-control" rows="3"
                            placeholder="Provide feedback...">{{ $e->faculty_weekly_remarks }}</textarea>
                        @elseif($item === 'Monthly appraisal')
                        <textarea name="faculty_appraisal_remarks" class="form-control" rows="3"
                            placeholder="Provide feedback...">{{ $e->faculty_appraisal_remarks }}</textarea>
                        @elseif($item === 'Supervisor evaluation')
                        <textarea name="faculty_supervisor_eval_remarks" class="form-control" rows="3"
                            placeholder="Provide remarks...">{{ $e->faculty_supervisor_eval_remarks }}</textarea>
                        @elseif($item === 'Certificate of completion')
                        <textarea name="faculty_coc_remarks" class="form-control" rows="3"
                            placeholder="Provide remarks...">{{ $e->faculty_coc_remarks }}</textarea>
                        @else
                        <textarea name="faculty_remarks" class="form-control" rows="3"
                            placeholder="Add remarks...">{{ $e->faculty_remarks }}</textarea>
                        @endif
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-success"><i class="fas fa-check-circle"></i> Save Review</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

<script>
document.querySelectorAll('form[action*="/checklist/"]').forEach(function(form) {
    form.addEventListener('submit', function(e) {
        var statusSelect = form.querySelector('select[name="faculty_status"]');
        if (!statusSelect || statusSelect.value !== 'declined') return;

        var remarksField = form.querySelector('textarea[name="faculty_weekly_remarks"]')
            || form.querySelector('textarea[name="faculty_remarks"]')
            || form.querySelector('textarea[name="faculty_appraisal_remarks"]')
            || form.querySelector('textarea[name="faculty_supervisor_eval_remarks"]')
            || form.querySelector('textarea[name="faculty_coc_remarks"]');

        if (remarksField && remarksField.value.trim() === '') {
            e.preventDefault();
            remarksField.classList.add('is-invalid');
            if (!remarksField.nextElementSibling || !remarksField.nextElementSibling.classList.contains('text-danger')) {
                var div = document.createElement('div');
                div.className = 'text-danger small mt-1';
                div.textContent = 'Please provide a reason when declining.';
                remarksField.parentNode.insertBefore(div, remarksField.nextSibling);
            }
            remarksField.focus();
        }
    });

    var statusSelect = form.querySelector('select[name="faculty_status"]');
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            var remarksField = form.querySelector('textarea[name="faculty_weekly_remarks"]')
                || form.querySelector('textarea[name="faculty_remarks"]')
                || form.querySelector('textarea[name="faculty_appraisal_remarks"]')
                || form.querySelector('textarea[name="faculty_supervisor_eval_remarks"]')
                || form.querySelector('textarea[name="faculty_coc_remarks"]');
            if (remarksField) {
                remarksField.classList.remove('is-invalid');
                var msg = remarksField.nextElementSibling;
                if (msg && msg.classList.contains('text-danger')) msg.remove();
            }
        });
    }
});
</script>

@endsection
