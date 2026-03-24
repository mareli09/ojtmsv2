@extends('layouts.admin')

@section('title', 'Dashboard - Faculty')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
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
<div class="container-fluid">
    <h2><i class="fas fa-tachometer-alt"></i> Faculty Dashboard</h2>

    {{-- Welcome --}}
    <div class="card mb-4" style="border-top: 5px solid var(--ojtms-accent);">
        <div class="card-body">
            <h3>Welcome, {{ $user->first_name }} {{ $user->last_name }}!</h3>
            <p class="text-muted mb-0">
                <i class="fas fa-info-circle"></i> You handle <strong>{{ $sections->count() }}</strong> section(s) with <strong>{{ $studentsCount }}</strong> enrolled students.
            </p>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="row mb-4">
        <div class="col-lg col-md-4 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-primary">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Students</small>
                            <h3 class="mb-0 fw-bold">{{ $studentsCount }}</h3>
                        </div>
                        <i class="fas fa-users fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-info">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Total Submissions</small>
                            <h3 class="mb-0 fw-bold">{{ $totalSubmissions }}</h3>
                        </div>
                        <i class="fas fa-file-upload fa-2x text-info opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg col-md-4 col-6 mb-3">
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
        <div class="col-lg col-md-4 col-6 mb-3">
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
        <div class="col-lg col-md-4 col-6 mb-3">
            <div class="card h-100 border-start border-4 border-danger">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted">Incidents</small>
                            <h3 class="mb-0 fw-bold text-danger">{{ $pendingIncidents }}</h3>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Submission Status Chart --}}
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i>Submission Status</h6></div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    @if($totalSubmissions == 0)
                    <p class="text-muted">No submissions yet.</p>
                    @else
                    <canvas id="statusChart" width="220" height="220"></canvas>
                    <div class="d-flex gap-3 mt-3 flex-wrap justify-content-center" style="font-size:13px">
                        <span><i class="fas fa-circle text-success"></i> Approved ({{ $approvedCount }})</span>
                        <span><i class="fas fa-circle text-warning"></i> Pending ({{ $pendingCount }})</span>
                        <span><i class="fas fa-circle text-danger"></i> Declined ({{ $declinedCount }})</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Checklist Item Breakdown --}}
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0"><i class="fas fa-tasks me-2"></i>Submissions by Checklist Item</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item</th>
                                    <th class="text-center">Total</th>
                                    <th class="text-center">Approved</th>
                                    <th class="text-center">Pending</th>
                                    <th class="text-center">Declined</th>
                                    <th>Distribution</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($itemStats as $item => $stat)
                                <tr>
                                    <td><small>{{ $item }}</small></td>
                                    <td class="text-center"><strong>{{ $stat['total'] }}</strong></td>
                                    <td class="text-center"><span class="text-success">{{ $stat['approved'] }}</span></td>
                                    <td class="text-center"><span class="text-warning">{{ $stat['pending'] }}</span></td>
                                    <td class="text-center"><span class="text-danger">{{ $stat['declined'] }}</span></td>
                                    <td>
                                        @if($stat['total'] > 0)
                                        @php $t = $stat['total']; @endphp
                                        <div class="progress" style="height:14px;">
                                            <div class="progress-bar bg-success" style="width:{{ ($stat['approved']/$t)*100 }}%"></div>
                                            <div class="progress-bar bg-warning" style="width:{{ ($stat['pending']/$t)*100 }}%"></div>
                                            <div class="progress-bar bg-danger" style="width:{{ ($stat['declined']/$t)*100 }}%"></div>
                                        </div>
                                        @else
                                        <small class="text-muted">—</small>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        {{-- Student Progress Ranking --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0"><i class="fas fa-trophy me-2"></i>Student Progress Ranking</h6></div>
                <div class="card-body p-0">
                    @if(count($studentProgress) == 0)
                    <p class="text-muted p-3 mb-0">No students.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach(array_slice($studentProgress, 0, 8) as $i => $s)
                        @php
                            $barColor = $s['pct'] >= 80 ? 'success' : ($s['pct'] >= 40 ? 'primary' : 'warning');
                        @endphp
                        <li class="list-group-item d-flex align-items-center gap-2">
                            <span class="badge bg-{{ $i < 3 ? 'success' : 'secondary' }} rounded-circle" style="width:26px;height:26px;display:flex;align-items:center;justify-content:center;">{{ $i + 1 }}</span>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <small class="fw-bold">{{ $s['name'] }}</small>
                                    <small class="text-muted">{{ $s['completed'] }}/{{ $s['total'] }} items &bull; {{ number_format($s['dtr_hours'], 0) }} hrs</small>
                                </div>
                                <div class="progress mt-1" style="height:8px;">
                                    <div class="progress-bar bg-{{ $barColor }}" style="width:{{ $s['pct'] }}%"></div>
                                </div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>

        {{-- Recent Activity --}}
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0"><i class="fas fa-history me-2"></i>Recent Submissions</h6></div>
                <div class="card-body p-0">
                    @if($recentActivity->count() == 0)
                    <p class="text-muted p-3 mb-0">No activity yet.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($recentActivity as $entry)
                        @php
                            $sm = ['pending' => ['warning', 'clock'], 'approved' => ['success', 'check-circle'], 'declined' => ['danger', 'times-circle']];
                            [$sc, $si] = $sm[$entry->faculty_status] ?? ['secondary', 'question-circle'];
                        @endphp
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <i class="fas fa-{{ $si }} text-{{ $sc }} me-1"></i>
                                    <strong>{{ $entry->student_name }}</strong> — {{ $entry->item }}
                                </div>
                                <span class="badge bg-{{ $sc }}">{{ ucfirst($entry->faculty_status) }}</span>
                            </div>
                            <small class="text-muted">{{ $entry->updated_at->format('M d, Y g:i A') }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="mb-4">
        <h5 class="mb-3"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
        <div class="d-flex flex-wrap gap-2">
            <a href="/faculty/section" class="btn btn-primary"><i class="fas fa-book-open me-1"></i>Manage Sections</a>
            <a href="/faculty/incident-reports" class="btn btn-danger"><i class="fas fa-exclamation-triangle me-1"></i>Incident Reports</a>
            <a href="/faculty/chatbot" class="btn btn-dark"><i class="fas fa-robot me-1"></i>AI Chatbot</a>
            <a href="/faculty/decision-support" class="btn btn-info text-white"><i class="fas fa-brain me-1"></i>Decision Support</a>
        </div>
    </div>
</div>

<style>
.card { border:none;box-shadow:0 2px 10px rgba(0,1,86,.08);border-radius:8px; }
.card-header { background-color:var(--ojtms-light);border-bottom:2px solid var(--ojtms-accent);color:var(--ojtms-primary);font-weight:600; }
</style>

<script>
(function() {
    const canvas = document.getElementById('statusChart');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    const data = [{{ $approvedCount }}, {{ $pendingCount }}, {{ $declinedCount }}];
    const colors = ['#198754', '#ffc107', '#dc3545'];
    const total = data.reduce((a,b) => a+b, 0);
    if (total === 0) return;
    const cx = 110, cy = 110, outerR = 100, innerR = 55;
    let start = -Math.PI / 2;
    data.forEach(function(val, i) {
        if (val === 0) return;
        const slice = (val / total) * 2 * Math.PI;
        ctx.beginPath();
        ctx.moveTo(cx + innerR * Math.cos(start), cy + innerR * Math.sin(start));
        ctx.arc(cx, cy, outerR, start, start + slice);
        ctx.arc(cx, cy, innerR, start + slice, start, true);
        ctx.closePath();
        ctx.fillStyle = colors[i];
        ctx.fill();
        start += slice;
    });
    ctx.fillStyle = '#333';
    ctx.font = 'bold 24px sans-serif';
    ctx.textAlign = 'center';
    ctx.textBaseline = 'middle';
    ctx.fillText(total, cx, cy - 8);
    ctx.font = '11px sans-serif';
    ctx.fillStyle = '#888';
    ctx.fillText('Total', cx, cy + 12);
})();
</script>
@endsection
