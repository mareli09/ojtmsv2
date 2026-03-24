@extends('layouts.student')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-tachometer-alt"></i> Student Dashboard</h2>

    {{-- Welcome Card --}}
    <div class="card mb-4" style="border-top: 5px solid var(--ojtms-accent);">
        <div class="card-body">
            <h3>Welcome, {{ $user->first_name }} {{ $user->last_name }}!</h3>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> You are logged in as <strong>{{ ucfirst($user->role) }}</strong>
            </p>
        </div>
    </div>

    {{-- Student ID & Department --}}
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center py-3">
                    <h6 class="text-muted mb-1">Student ID</h6>
                    <h4 class="mb-0 fw-bold">{{ $user->student_id ?? 'N/A' }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body text-center py-3">
                    <h6 class="text-muted mb-1">Department</h6>
                    <h4 class="mb-0 fw-bold">{{ $user->department ?? 'N/A' }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Submissions</small>
                            <h3 class="mb-0 fw-bold">{{ $totalSubmissions }}</h3>
                        </div>
                        <i class="fas fa-file-upload fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-success">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Approved</small>
                            <h3 class="mb-0 fw-bold text-success">{{ $approvedCount }}</h3>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-warning">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Pending Review</small>
                            <h3 class="mb-0 fw-bold text-warning">{{ $pendingCount }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-danger">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Declined</small>
                            <h3 class="mb-0 fw-bold text-danger">{{ $declinedCount }}</h3>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Checklist Progress --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-tasks me-2"></i>Checklist Progress</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <div class="position-relative d-inline-block" style="width:140px;height:140px;">
                            <canvas id="progressRing" width="140" height="140"></canvas>
                            <div class="position-absolute top-50 start-50 translate-middle text-center">
                                <h3 class="mb-0 fw-bold">{{ $completedCount }}/{{ count($checklistItems) }}</h3>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($checklistItems as $item)
                        <div class="col-6 mb-1">
                            @if($completedItems[$item])
                            <small class="text-success"><i class="fas fa-check-circle me-1"></i>{{ $item }}</small>
                            @else
                            <small class="text-muted"><i class="far fa-circle me-1"></i>{{ $item }}</small>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- OJT Hours Tracker --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-clock me-2"></i>OJT Hours Tracker</h5>
                </div>
                <div class="card-body">
                    @php
                        $hoursPercent = $targetHours > 0 ? min(100, round(($totalHoursWorked / $targetHours) * 100)) : 0;
                        $hoursColor = $hoursPercent >= 100 ? 'success' : ($hoursPercent >= 50 ? 'primary' : 'warning');
                    @endphp
                    <div class="text-center mb-3">
                        <h2 class="fw-bold mb-0">{{ number_format($totalHoursWorked, 1) }} <small class="text-muted fs-6">/ {{ $targetHours }} hrs</small></h2>
                        <small class="text-muted">Approved hours recorded</small>
                    </div>
                    <div class="progress mb-2" style="height:24px;border-radius:12px;">
                        <div class="progress-bar bg-{{ $hoursColor }} progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width:{{ $hoursPercent }}%;border-radius:12px;" aria-valuenow="{{ $hoursPercent }}" aria-valuemin="0" aria-valuemax="100">
                            {{ $hoursPercent }}%
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">0 hrs</small>
                        <small class="text-muted">{{ $targetHours }} hrs</small>
                    </div>
                    @if($hoursPercent >= 100)
                    <div class="alert alert-success mt-3 mb-0 text-center">
                        <i class="fas fa-trophy me-1"></i> Target hours completed!
                    </div>
                    @else
                    <p class="text-muted mt-3 mb-0 text-center"><small><i class="fas fa-info-circle me-1"></i>{{ number_format($targetHours - $totalHoursWorked, 1) }} hours remaining</small></p>
                    @endif

                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">{{ $weeklyCount }}</h5>
                            <small class="text-muted">Weekly Reports</small>
                        </div>
                        <div class="col-6">
                            <h5 class="fw-bold mb-0">{{ $appraisalCount }}</h5>
                            <small class="text-muted">Monthly Appraisals</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Submission Analytics (Chart) --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Submission Status Breakdown</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if($totalSubmissions == 0)
                    <p class="text-muted mb-0">No submissions yet.</p>
                    @else
                    <canvas id="statusChart" width="260" height="260"></canvas>
                    <div class="d-flex gap-3 mt-3 flex-wrap justify-content-center">
                        <span><i class="fas fa-circle text-success"></i> Approved ({{ $approvedCount }})</span>
                        <span><i class="fas fa-circle text-warning"></i> Pending ({{ $pendingCount }})</span>
                        <span><i class="fas fa-circle text-danger"></i> Declined ({{ $declinedCount }})</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i>Recent Activity</h5>
                </div>
                <div class="card-body p-0">
                    @if($recentActivity->count() == 0)
                    <p class="text-muted p-3 mb-0">No activity yet.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivity as $entry)
                        @php
                            $statusMap = [
                                'pending'  => ['warning', 'clock', 'Pending'],
                                'approved' => ['success', 'check-circle', 'Approved'],
                                'declined' => ['danger', 'times-circle', 'Declined'],
                            ];
                            [$sc, $si, $sl] = $statusMap[$entry->faculty_status] ?? ['secondary', 'question-circle', $entry->faculty_status];
                        @endphp
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-{{ $si }} text-{{ $sc }} me-2"></i>
                                <strong>{{ $entry->item }}</strong>
                                <br><small class="text-muted ms-4">{{ $entry->updated_at->format('M d, Y g:i A') }}</small>
                            </div>
                            <span class="badge bg-{{ $sc }}">{{ $sl }}</span>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Section & Faculty Info --}}
    @if($section)
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users me-2"></i>Assigned Section</h5>
                </div>
                <div class="card-body">
                    <p><strong>Section:</strong> {{ $section->name }}</p>
                    <p><strong>School Year:</strong> {{ $section->school_year }}</p>
                    <p><strong>Term:</strong> {{ $section->term }}</p>
                    <p><strong>Room:</strong> {{ $section->room }}</p>
                    <p class="mb-0"><strong>Schedule:</strong> {{ $section->day }} ({{ $section->start_time }} - {{ $section->end_time }})</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i>Faculty Instructor</h5>
                </div>
                <div class="card-body">
                    @if($faculty)
                        <p><strong>Name:</strong> {{ $faculty->first_name }} {{ $faculty->last_name }}</p>
                        <p><strong>Employee ID:</strong> {{ $faculty->employee_id ?? 'N/A' }}</p>
                        <p><strong>Email:</strong> {{ $faculty->email }}</p>
                        <p class="mb-0"><strong>Contact:</strong> {{ $faculty->contact ?? 'N/A' }}</p>
                    @else
                        <p class="text-muted mb-0"><i class="fas fa-info-circle"></i> No faculty assigned yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle"></i> You are not enrolled in any section yet.
    </div>
    @endif

    {{-- Quick Actions --}}
    <div class="mb-4">
        <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="/student/dtr" class="btn btn-primary"><i class="fas fa-clock me-1"></i> Daily Time Record</a>
            <a href="/student/weekly-report" class="btn btn-info text-white"><i class="fas fa-file-alt me-1"></i> Weekly Reports</a>
            <a href="/student/monthly-appraisal" class="btn btn-warning"><i class="fas fa-star me-1"></i> Monthly Appraisal</a>
            <a href="/student/checklist" class="btn btn-success"><i class="fas fa-tasks me-1"></i> Checklist</a>
            <a href="/student/incident-report" class="btn btn-danger"><i class="fas fa-exclamation-triangle me-1"></i> Incident Report</a>
        </div>
    </div>
</div>

<style>
.card {
    border: none;
    box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
    border-radius: 8px;
}
.card-header {
    background-color: var(--ojtms-light);
    border-bottom: 2px solid var(--ojtms-accent);
    color: var(--ojtms-primary);
    font-weight: 600;
}
</style>

<script>
// Progress Ring
(function() {
    const canvas = document.getElementById('progressRing');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const cx = 70, cy = 70, r = 56, lw = 12;
    const completed = {{ $completedCount }};
    const total = {{ count($checklistItems) }};
    const pct = total > 0 ? completed / total : 0;

    // Background ring
    ctx.beginPath();
    ctx.arc(cx, cy, r, 0, 2 * Math.PI);
    ctx.lineWidth = lw;
    ctx.strokeStyle = '#e9ecef';
    ctx.stroke();

    // Progress ring
    if (pct > 0) {
        ctx.beginPath();
        ctx.arc(cx, cy, r, -Math.PI/2, -Math.PI/2 + 2 * Math.PI * pct);
        ctx.lineWidth = lw;
        ctx.lineCap = 'round';
        ctx.strokeStyle = pct >= 1 ? '#198754' : (pct >= 0.5 ? '#0d6efd' : '#ffc107');
        ctx.stroke();
    }
})();

// Doughnut Chart
(function() {
    const canvas = document.getElementById('statusChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const data = [{{ $approvedCount }}, {{ $pendingCount }}, {{ $declinedCount }}];
    const colors = ['#198754', '#ffc107', '#dc3545'];
    const total = data.reduce((a,b) => a + b, 0);
    if (total === 0) return;

    const cx = 130, cy = 130, outerR = 110, innerR = 60;
    let startAngle = -Math.PI / 2;

    data.forEach(function(val, i) {
        if (val === 0) return;
        const slice = (val / total) * 2 * Math.PI;
        ctx.beginPath();
        ctx.moveTo(cx + innerR * Math.cos(startAngle), cy + innerR * Math.sin(startAngle));
        ctx.arc(cx, cy, outerR, startAngle, startAngle + slice);
        ctx.arc(cx, cy, innerR, startAngle + slice, startAngle, true);
        ctx.closePath();
        ctx.fillStyle = colors[i];
        ctx.fill();
        startAngle += slice;
    });

    // Center text
    ctx.fillStyle = '#333';
    ctx.font = 'bold 28px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(total, cx, cy - 8);
    ctx.font = '12px sans-serif';
    ctx.fillStyle = '#888';
    ctx.fillText('Total', cx, cy + 14);
})();
</script>
@endsection
