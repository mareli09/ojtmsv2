@extends('layouts.admin')

@section('title', 'Dashboard')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<style>
    /* Chart / data-display tokens for this page */
    .dash {
        --ink:          #0b0b0b;
        --ink-muted:    #6d6c68;
        --rule:         #e1e0d9;
        --good:         #0ca30c;
        --warn:         #fab219;
        --critical:     #d03b3b;
        --track:        #eef0f3;
    }

    .dash .stat-card { text-align: left; padding: 20px 22px; }
    .dash .stat-card h5 { margin-bottom: 6px; font-size: .78rem; letter-spacing: .04em; }
    .dash .stat-card h3 { font-size: 2.2rem; margin-bottom: 2px; }
    .dash .stat-caption { color: var(--ink-muted); font-size: .82rem; margin: 0; }

    .dash .attention-item {
        display: flex; align-items: center; gap: 12px;
        padding: 12px 20px; border-bottom: 1px solid var(--rule);
    }
    .dash .attention-item:last-child { border-bottom: none; }
    .dash .attention-icon {
        width: 34px; height: 34px; flex: 0 0 34px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center; font-size: .9rem;
    }
    .dash .lvl-critical .attention-icon { background: rgba(208,59,59,.12); color: var(--critical); }
    .dash .lvl-warning  .attention-icon { background: rgba(250,178,25,.18); color: #8a6100; }
    .dash .lvl-info     .attention-icon { background: rgba(42,120,214,.12); color: #2a78d6; }
    .dash .attention-text { flex: 1; color: var(--ink); font-size: .92rem; }

    /* Requirement progress rows: the bar and the numbers say the same thing */
    .dash .req-row { padding: 10px 0; border-bottom: 1px solid var(--rule); }
    .dash .req-row:last-child { border-bottom: none; }
    .dash .req-name { font-size: .88rem; color: var(--ink); font-weight: 600; }
    .dash .req-counts { font-size: .8rem; color: var(--ink-muted); font-variant-numeric: tabular-nums; }
    .dash .req-bar {
        display: flex; gap: 2px; height: 10px; margin-top: 6px;
        background: var(--track); border-radius: 5px; overflow: hidden;
    }
    .dash .req-seg { height: 100%; }
    .dash .seg-approved { background: var(--good); }
    .dash .seg-pending  { background: var(--warn); }
    .dash .seg-declined { background: var(--critical); }

    .dash .legend-key { display: inline-flex; align-items: center; gap: 6px; font-size: .8rem; color: var(--ink-muted); margin-right: 14px; }
    .dash .legend-dot { width: 10px; height: 10px; border-radius: 2px; display: inline-block; }

    .dash .mini-table td, .dash .mini-table th { font-size: .85rem; vertical-align: middle; }
    .dash .empty-note { color: var(--ink-muted); font-size: .88rem; padding: 18px; text-align: center; }
    .dash .card:hover { transform: none; }
</style>

<div class="container-fluid dash">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
            <small class="text-muted">{{ now()->format('l, F d, Y \a\t g:i A') }}</small>
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/users/create" class="btn btn-primary btn-sm"><i class="fas fa-user-plus"></i> Add User</a>
            <a href="/admin/sections/create" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> New Section</a>
            <a href="/admin/announcements/create" class="btn btn-secondary btn-sm"><i class="fas fa-bullhorn"></i> Announce</a>
            <a href="/admin/reports" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Reports</a>
        </div>
    </div>

    <!-- ===== KPI TILES ===== -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-users h-100">
                <h5>Total Users</h5>
                <h3>{{ $totalUsers }}</h3>
                <p class="stat-caption">
                    {{ $activeThisWeek }} active in the last 7 days
                    @if($inactiveUsers > 0) &middot; {{ $inactiveUsers }} inactive @endif
                </p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-students h-100">
                <h5>Students</h5>
                <h3>{{ $students }}</h3>
                <p class="stat-caption">
                    {{ $students - $studentsNoSection }} enrolled in a section
                    @if($studentsNoSection > 0) &middot; {{ $studentsNoSection }} unassigned @endif
                </p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-faculty h-100">
                <h5>Faculty</h5>
                <h3>{{ $faculty }}</h3>
                <p class="stat-caption">{{ $sections->whereNotNull('faculty_id')->count() }} handling a section</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-admins h-100">
                <h5>Sections</h5>
                <h3>{{ $sections->count() }}</h3>
                <p class="stat-caption">
                    {{ $activeSections }} active
                    @if($sectionsNoFaculty > 0) &middot; {{ $sectionsNoFaculty }} without faculty @endif
                </p>
            </div>
        </div>
    </div>

    <!-- ===== NEEDS ATTENTION ===== -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list-check me-2"></i>Needs Your Attention</span>
                    <span class="badge" style="background:var(--ojtms-primary); color:var(--ojtms-accent);">{{ count($attention) }}</span>
                </div>
                <div class="card-body p-0">
                    @forelse($attention as $item)
                        <div class="attention-item lvl-{{ $item['level'] }}">
                            <span class="attention-icon"><i class="fas {{ $item['icon'] }}"></i></span>
                            <span class="attention-text">{{ $item['text'] }}</span>
                            <a href="{{ $item['link'] }}" class="btn btn-sm btn-secondary">{{ $item['action'] }}</a>
                        </div>
                    @empty
                        <div class="empty-note">
                            <i class="fas fa-circle-check fa-lg mb-2 d-block" style="color:var(--good);"></i>
                            Everything is in order — no pending assignments, reviews, or incidents.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- OJT PROGRESS SUMMARY -->
        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="fas fa-graduation-cap me-2"></i>OJT Progress</div>
                <div class="card-body">
                    @if($submissionTotal === 0)
                        <div class="empty-note">No document submissions yet.</div>
                    @else
                        @php
                            $approvedPct = round($approvedTotal / $submissionTotal * 100);
                        @endphp
                        <p class="mb-1" style="font-size:.85rem; color:var(--ink-muted);">Document submissions reviewed</p>
                        <h3 class="fw-bold mb-1" style="color:var(--ojtms-primary);">{{ $approvedPct }}%</h3>
                        <p class="stat-caption mb-3">{{ $approvedTotal }} of {{ $submissionTotal }} submissions approved</p>

                        <div class="req-bar mb-2" style="height:14px;">
                            @if($approvedTotal) <div class="req-seg seg-approved" style="width: {{ $approvedTotal / $submissionTotal * 100 }}%;"></div> @endif
                            @if($pendingTotal)  <div class="req-seg seg-pending"  style="width: {{ $pendingTotal / $submissionTotal * 100 }}%;"></div> @endif
                            @if($declinedTotal) <div class="req-seg seg-declined" style="width: {{ $declinedTotal / $submissionTotal * 100 }}%;"></div> @endif
                        </div>

                        <div class="mb-3">
                            <span class="legend-key"><i class="fas fa-circle-check" style="color:var(--good);"></i> Approved {{ $approvedTotal }}</span>
                            <span class="legend-key"><i class="fas fa-clock" style="color:#8a6100;"></i> Pending {{ $pendingTotal }}</span>
                            @if($declinedTotal > 0)
                                <span class="legend-key"><i class="fas fa-circle-xmark" style="color:var(--critical);"></i> Declined {{ $declinedTotal }}</span>
                            @endif
                        </div>
                    @endif

                    <hr>
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold" style="font-size:1.3rem; color:var(--ojtms-primary);">{{ $studentsComplete }}</div>
                            <small class="text-muted">Completed all {{ $requirementCount }}</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold" style="font-size:1.3rem; color:var(--ojtms-primary);">{{ $studentsStarted }}</div>
                            <small class="text-muted">Have approvals</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold" style="font-size:1.3rem; color:{{ $incidentsPending > 0 ? 'var(--critical)' : 'var(--ojtms-primary)' }};">{{ $incidentsTotal }}</div>
                            <small class="text-muted">Incident reports</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CHARTS ===== -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-3">
            <div class="card h-100">
                <div class="card-header">New User Registrations &mdash; last 6 months</div>
                <div class="card-body">
                    @if(array_sum($trendData) === 0)
                        <div class="empty-note">
                            No accounts were created in the last 6 months.<br>
                            @if($recentUsers->isNotEmpty())
                                The most recent of the {{ $totalUsers }} existing
                                {{ Str::plural('account', $totalUsers) }} was added
                                {{ $recentUsers->first()->created_at->diffForHumans() }}.
                            @endif
                        </div>
                    @else
                        <canvas id="registrationChart" height="90"></canvas>
                        <p class="stat-caption mt-2 mb-0">
                            {{ array_sum($trendData) }} {{ Str::plural('account', array_sum($trendData)) }} created in this period.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-header">User Distribution</div>
                <div class="card-body">
                    @if($totalUsers === 0)
                        <div class="empty-note">No users yet.</div>
                    @else
                        <canvas id="distributionChart" height="200"></canvas>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== REQUIREMENT PROGRESS ===== -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="fas fa-folder-open me-2"></i>Submissions per Requirement</div>
                <div class="card-body">
                    <div class="mb-2">
                        <span class="legend-key"><span class="legend-dot" style="background:var(--good);"></span> Approved</span>
                        <span class="legend-key"><span class="legend-dot" style="background:var(--warn);"></span> Pending</span>
                        <span class="legend-key"><span class="legend-dot" style="background:var(--critical);"></span> Declined</span>
                    </div>
                    @foreach($requirementStats as $req)
                        @php $reqTotal = $req['approved'] + $req['pending'] + $req['declined']; @endphp
                        <div class="req-row">
                            <div class="d-flex justify-content-between align-items-center gap-2">
                                <span class="req-name">{{ $req['item'] }}</span>
                                <span class="req-counts">
                                    @if($reqTotal === 0)
                                        none yet
                                    @else
                                        {{ $req['approved'] }} approved
                                        @if($req['pending']) &middot; {{ $req['pending'] }} pending @endif
                                        @if($req['declined']) &middot; {{ $req['declined'] }} declined @endif
                                    @endif
                                </span>
                            </div>
                            @if($reqTotal > 0)
                                <div class="req-bar">
                                    @if($req['approved']) <div class="req-seg seg-approved" style="width: {{ $req['approved'] / $reqTotal * 100 }}%;"></div> @endif
                                    @if($req['pending'])  <div class="req-seg seg-pending"  style="width: {{ $req['pending'] / $reqTotal * 100 }}%;"></div> @endif
                                    @if($req['declined']) <div class="req-seg seg-declined" style="width: {{ $req['declined'] / $reqTotal * 100 }}%;"></div> @endif
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION OVERVIEW -->
        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-book me-2"></i>Sections</span>
                    <a href="/admin/sections" class="btn btn-sm btn-secondary">Manage</a>
                </div>
                <div class="card-body p-0">
                    @if($sections->isEmpty())
                        <div class="empty-note">
                            No sections yet. <a href="/admin/sections/create">Create the first one</a>.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 mini-table">
                                <thead>
                                    <tr>
                                        <th>Section</th>
                                        <th>Faculty</th>
                                        <th class="text-end">Students</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sections as $section)
                                    <tr>
                                        <td>
                                            <strong>{{ $section->name }}</strong><br>
                                            <small class="text-muted">{{ $section->day }}</small>
                                        </td>
                                        <td>
                                            @if($section->faculty)
                                                {{ $section->faculty->first_name }} {{ $section->faculty->last_name }}
                                            @else
                                                <span style="color:var(--critical);"><i class="fas fa-circle-exclamation me-1"></i>Unassigned</span>
                                            @endif
                                        </td>
                                        <td class="text-end" style="font-variant-numeric: tabular-nums;">
                                            {{ $section->students_count }}@if($section->capacity)<span class="text-muted">/{{ $section->capacity }}</span>@endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== RECENT ACTIVITY ===== -->
    <div class="row mb-4">
        <div class="col-lg-7 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="fas fa-clock-rotate-left me-2"></i>Latest Submissions</div>
                <div class="card-body p-0">
                    @if($recentSubmissions->isEmpty())
                        <div class="empty-note">No submissions have been made yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 mini-table">
                                <thead>
                                    <tr>
                                        <th>Student</th>
                                        <th>Requirement</th>
                                        <th>Status</th>
                                        <th>Submitted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubmissions as $submission)
                                    <tr>
                                        <td>
                                            @if($submission->student)
                                                {{ $submission->student->first_name }} {{ $submission->student->last_name }}
                                            @else
                                                <span class="text-muted">Unknown</span>
                                            @endif
                                        </td>
                                        <td>{{ $submission->item }}</td>
                                        <td>
                                            @if($submission->faculty_status === 'approved')
                                                <span style="color:var(--good);"><i class="fas fa-circle-check me-1"></i>Approved</span>
                                            @elseif($submission->faculty_status === 'declined')
                                                <span style="color:var(--critical);"><i class="fas fa-circle-xmark me-1"></i>Declined</span>
                                            @else
                                                <span style="color:#8a6100;"><i class="fas fa-clock me-1"></i>Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-muted">{{ \Carbon\Carbon::parse($submission->student_submitted_at)->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 mb-3">
            <div class="card h-100">
                <div class="card-header"><i class="fas fa-user-plus me-2"></i>Newest Accounts</div>
                <div class="card-body p-0">
                    @if($recentUsers->isEmpty())
                        <div class="empty-note">No users yet.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 mini-table">
                                <tbody>
                                    @foreach($recentUsers as $user)
                                    <tr>
                                        <td>
                                            <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
                                            <small class="text-muted">{{ ucfirst($user->role) }}</small>
                                        </td>
                                        <td class="text-end text-muted">{{ $user->created_at->diffForHumans() }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- ===== CONTENT SNAPSHOT ===== -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body d-flex flex-wrap gap-4 justify-content-around text-center">
                    <div>
                        <div class="fw-bold" style="font-size:1.4rem; color:var(--ojtms-primary);">{{ $activeAnnouncements }}</div>
                        <small class="text-muted">Active announcements</small>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.4rem; color:var(--ojtms-primary);">{{ $faqCount }}</div>
                        <small class="text-muted">Published FAQs</small>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.4rem; color:var(--ojtms-primary);">{{ $submissionTotal }}</div>
                        <small class="text-muted">Total submissions</small>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size:1.4rem; color:var(--ojtms-primary);">{{ $requirementCount }}</div>
                        <small class="text-muted">Tracked requirements</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    const INK_MUTED = '#6d6c68';
    const RULE      = '#e1e0d9';

    Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
    Chart.defaults.color = INK_MUTED;

    const tooltip = {
        backgroundColor: '#0a0a29',
        titleColor: '#ffffff',
        bodyColor: '#ffffff',
        padding: 10,
        displayColors: true,
        boxPadding: 4
    };

    // --- New user registrations (single series: no legend needed) ---
    const trendCanvas = document.getElementById('registrationChart');
    if (trendCanvas) {
        new Chart(trendCanvas.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [{
                    label: 'New users',
                    data: @json($trendData),
                    borderColor: '#2a78d6',
                    backgroundColor: 'rgba(42, 120, 214, 0.08)',
                    borderWidth: 2,
                    tension: 0.35,
                    fill: true,
                    pointBackgroundColor: '#2a78d6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { display: false }, tooltip: tooltip },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: INK_MUTED },
                        grid: { color: RULE, drawTicks: false },
                        border: { display: false }
                    },
                    x: {
                        ticks: { color: INK_MUTED },
                        grid: { display: false },
                        border: { color: '#c3c2b7' }
                    }
                }
            }
        });
    }

    // --- User distribution (3 categorical slots, labelled in the legend) ---
    const distCanvas = document.getElementById('distributionChart');
    if (distCanvas) {
        const counts = [{{ $admins }}, {{ $faculty }}, {{ $students }}];
        const names  = ['Admin', 'Faculty', 'Student'];

        new Chart(distCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: names.map((n, i) => n + ' (' + counts[i] + ')'),
                datasets: [{
                    data: counts,
                    backgroundColor: ['#2a78d6', '#eb6834', '#1baf7a'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                cutout: '58%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { color: INK_MUTED, padding: 14, boxWidth: 12, boxHeight: 12 }
                    },
                    tooltip: tooltip
                }
            }
        });
    }
})();
</script>
@endpush
@endsection
