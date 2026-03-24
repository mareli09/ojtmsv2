@extends('layouts.admin')

@section('title', 'Decision Support System')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link active"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-brain"></i> Decision Support System</h2>
    <p class="text-muted">AI-powered student performance analysis and actionable recommendations.</p>

    @if(!$aiEnabled)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>AI features are disabled.</strong> The student data table below is still available, but AI analysis requires the administrator to enable OpenAI integration.
    </div>
    @endif

    {{-- Student Performance Table --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-users me-2"></i>Student Overview ({{ count($studentData) }} students)</span>
            <input type="text" id="studentSearch" class="form-control form-control-sm w-auto" placeholder="Search student...">
        </div>
        <div class="card-body p-0">
            @if(count($studentData) == 0)
            <p class="text-muted p-4 mb-0">No students found in your sections.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0" id="studentTable">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Section</th>
                            <th class="text-center">Checklist</th>
                            <th class="text-center">Hours</th>
                            <th class="text-center">Approved</th>
                            <th class="text-center">Pending</th>
                            <th class="text-center">Declined</th>
                            <th class="text-center">Incidents</th>
                            <th class="text-center">Risk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($studentData as $s)
                        @php
                            $pct = $s['total_items'] > 0 ? round(($s['completed_items'] / $s['total_items']) * 100) : 0;
                            $hPct = $s['hours_percent'];
                            // Simple risk scoring
                            $risk = 'low';
                            if ($pct < 30 || $hPct < 20 || $s['declined'] > 2 || $s['incidents'] > 1) $risk = 'high';
                            elseif ($pct < 60 || $hPct < 50 || $s['declined'] > 0) $risk = 'medium';
                            $riskColors = ['low' => 'success', 'medium' => 'warning', 'high' => 'danger'];
                            $riskLabels = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $s['name'] }}</strong><br>
                                <small class="text-muted">{{ $s['student_id'] }}</small>
                            </td>
                            <td>{{ $s['section'] }}</td>
                            <td class="text-center">
                                <div class="progress" style="height:18px;min-width:80px;">
                                    <div class="progress-bar bg-{{ $pct >= 80 ? 'success' : ($pct >= 40 ? 'primary' : 'warning') }}"
                                        style="width:{{ $pct }}%">{{ $s['completed_items'] }}/{{ $s['total_items'] }}</div>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="{{ $hPct >= 80 ? 'text-success' : ($hPct >= 40 ? 'text-primary' : 'text-warning') }} fw-bold">
                                    {{ number_format($s['dtr_hours'], 0) }}
                                </span>
                                <small class="text-muted">/{{ $s['target_hours'] }}</small>
                            </td>
                            <td class="text-center"><span class="badge bg-success">{{ $s['approved'] }}</span></td>
                            <td class="text-center"><span class="badge bg-warning text-dark">{{ $s['pending'] }}</span></td>
                            <td class="text-center"><span class="badge bg-danger">{{ $s['declined'] }}</span></td>
                            <td class="text-center">
                                @if($s['incidents'] > 0)
                                <span class="badge bg-danger">{{ $s['incidents'] }}</span>
                                @else
                                <span class="text-muted">0</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span class="badge bg-{{ $riskColors[$risk] }}">{{ $riskLabels[$risk] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- AI Analysis Panel --}}
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <i class="fas fa-brain me-2"></i>AI Analysis
            @if($aiEnabled)
            <span class="badge bg-success ms-2">Online</span>
            @else
            <span class="badge bg-secondary ms-2">Offline</span>
            @endif
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-8">
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-danger analysis-btn" data-type="at_risk" {{ !$aiEnabled ? 'disabled' : '' }}>
                            <i class="fas fa-exclamation-triangle me-1"></i>At-Risk Students
                        </button>
                        <button class="btn btn-outline-primary analysis-btn" data-type="performance" {{ !$aiEnabled ? 'disabled' : '' }}>
                            <i class="fas fa-chart-line me-1"></i>Performance Summary
                        </button>
                        <button class="btn btn-outline-success analysis-btn" data-type="priority" {{ !$aiEnabled ? 'disabled' : '' }}>
                            <i class="fas fa-tasks me-1"></i>Weekly Priorities
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" id="customPrompt" class="form-control form-control-sm"
                            placeholder="Or ask a custom question..." {{ !$aiEnabled ? 'disabled' : '' }}>
                        <button class="btn btn-sm btn-dark" id="customAnalyzeBtn" {{ !$aiEnabled ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div id="analysisLoading" class="text-center py-4" style="display:none">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="text-muted mt-2">Analyzing student data with AI... This may take a moment.</p>
            </div>

            <div id="analysisResult" style="display:none">
                <hr>
                <div id="analysisContent" class="p-3 bg-light rounded" style="white-space:pre-wrap;line-height:1.7;font-size:14px;"></div>
            </div>

            @if(!$aiEnabled)
            <div class="text-center text-muted py-3">
                <i class="fas fa-power-off fa-2x mb-2 d-block"></i>
                AI Analysis requires OpenAI to be enabled by the administrator.
            </div>
            @else
            <div id="analysisPlaceholder" class="text-center text-muted py-3">
                <i class="fas fa-brain fa-2x mb-2 d-block opacity-50"></i>
                Select an analysis type above or ask a custom question to get AI-powered insights.
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.card { border:none; box-shadow:0 2px 10px rgba(0,1,86,.08); border-radius:8px; }
.card-header { background-color:var(--ojtms-light);border-bottom:2px solid var(--ojtms-accent);color:var(--ojtms-primary);font-weight:600; }
.card-header.bg-dark { background-color:#212529!important;border-bottom:2px solid var(--ojtms-accent);color:#fff; }
</style>

<script>
// Student search filter
document.getElementById('studentSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#studentTable tbody tr').forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

// Build student data JSON for AI
const studentDataJson = @json($studentData);
const studentDataText = studentDataJson.map(s =>
    `${s.name} (${s.student_id}, Section ${s.section}): Checklist ${s.completed_items}/${s.total_items}, `
    + `Hours ${s.dtr_hours}/${s.target_hours} (${s.hours_percent}%), `
    + `Approved ${s.approved}, Pending ${s.pending}, Declined ${s.declined}, Incidents ${s.incidents}`
).join('\n');

async function runAnalysis(promptType, customPrompt) {
    document.getElementById('analysisLoading').style.display = '';
    document.getElementById('analysisResult').style.display = 'none';
    document.getElementById('analysisPlaceholder')?.remove();

    try {
        const body = {
            student_data: studentDataText,
            prompt_type: promptType,
            _token: '{{ csrf_token() }}'
        };
        if (customPrompt) body.custom_prompt = customPrompt;

        const res = await fetch('/faculty/decision-support/analyze', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify(body)
        });
        const data = await res.json();

        document.getElementById('analysisLoading').style.display = 'none';
        document.getElementById('analysisResult').style.display = '';
        document.getElementById('analysisContent').innerHTML = formatMarkdown(data.analysis);
    } catch(err) {
        document.getElementById('analysisLoading').style.display = 'none';
        document.getElementById('analysisResult').style.display = '';
        document.getElementById('analysisContent').innerHTML = '<span class="text-danger">Failed to connect. Please try again.</span>';
    }
}

function formatMarkdown(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/^### (.*$)/gm, '<h6 class="mt-3 mb-1">$1</h6>')
        .replace(/^## (.*$)/gm, '<h5 class="mt-3 mb-1">$1</h5>')
        .replace(/^# (.*$)/gm, '<h4 class="mt-3 mb-1">$1</h4>')
        .replace(/^- (.*$)/gm, '&bull; $1<br>')
        .replace(/^\d+\. (.*$)/gm, '<strong>$&</strong><br>')
        .replace(/\n/g, '<br>');
}

document.querySelectorAll('.analysis-btn').forEach(btn => {
    btn.addEventListener('click', () => runAnalysis(btn.dataset.type));
});

document.getElementById('customAnalyzeBtn').addEventListener('click', function() {
    const q = document.getElementById('customPrompt').value.trim();
    if (q) runAnalysis('custom', q);
});

document.getElementById('customPrompt').addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('customAnalyzeBtn').click();
    }
});
</script>
@endsection
