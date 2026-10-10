@extends('layouts.admin')

@section('title', 'Reports')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link active"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<style>
    .rep { --ink-muted:#6d6c68; --rule:#e1e0d9; }
    .rep .report-card { margin-bottom: 24px; }
    .rep .report-card:hover { transform: none; }
    .rep .summary-chip {
        display:inline-flex; align-items:baseline; gap:6px; margin:0 18px 0 0;
        font-size:.82rem; color:var(--ink-muted);
    }
    .rep .summary-chip strong {
        font-size:1.15rem; color:var(--ojtms-primary); font-variant-numeric: tabular-nums;
    }
    .rep table.report-table { font-size:.82rem; margin-bottom:0; }
    .rep table.report-table th {
        white-space:nowrap; font-size:.72rem; text-transform:uppercase; letter-spacing:.03em;
        color:var(--ojtms-primary); border-bottom:2px solid var(--ojtms-accent);
    }
    .rep table.report-table td { vertical-align:middle; font-variant-numeric: tabular-nums; }
    .rep .table-scroll { max-height:420px; overflow:auto; }
    .rep .empty-note { color:var(--ink-muted); font-size:.88rem; padding:24px; text-align:center; }
    .rep .row-count { font-size:.78rem; color:var(--ink-muted); }
    .rep .tag { font-size:.72rem; padding:2px 8px; border-radius:10px; white-space:nowrap; }
    .tag-good     { background:rgba(12,163,12,.12);  color:#0a7a0a; }
    .tag-warn     { background:rgba(250,178,25,.20); color:#8a6100; }
    .tag-critical { background:rgba(208,59,59,.12);  color:#d03b3b; }
    .tag-muted    { background:#edeef0;              color:#52514e; }
</style>

<div class="container-fluid rep">
    <div class="d-flex justify-content-between align-items-end flex-wrap gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-file-alt"></i> Reports</h2>
            <small class="text-muted">
                Live data for your section &mdash; generated {{ now()->format('F d, Y \a\t g:i A') }}
            </small>
        </div>
        <div class="d-flex gap-2">
            <a href="/faculty/reports/print/all" target="_blank" class="btn btn-secondary btn-sm">
                <i class="fas fa-print"></i> Print / Save as PDF
            </a>
            <a href="/faculty/reports/export-all" class="btn btn-primary btn-sm">
                <i class="fas fa-file-zipper"></i> Download All (ZIP)
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @foreach($catalogue as $key => $meta)
        @php $report = $reports[$key]; @endphp

        <div class="card report-card">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span>
                    <i class="fas {{ $meta['icon'] }} me-2"></i>{{ $meta['title'] }}
                    <span class="row-count ms-2">{{ count($report['rows']) }} {{ Str::plural('row', count($report['rows'])) }}</span>
                </span>
                <span class="d-flex gap-2">
                    <a href="/faculty/reports/print/{{ $key }}" target="_blank" class="btn btn-sm btn-secondary">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="/faculty/reports/export/{{ $key }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-file-csv"></i> CSV
                    </a>
                </span>
            </div>

            <div class="card-body pb-2">
                <p class="text-muted small mb-3">{{ $meta['description'] }}</p>

                @if($report['summary'])
                <div class="mb-3">
                    @foreach($report['summary'] as $label => $value)
                        <span class="summary-chip"><strong>{{ $value }}</strong> {{ $label }}</span>
                    @endforeach
                </div>
                @endif
            </div>

            @if(empty($report['rows']))
                <div class="empty-note">{{ $report['empty'] }}</div>
            @else
                <div class="table-responsive table-scroll">
                    <table class="table table-hover table-sm report-table mb-0">
                        <thead class="sticky-top bg-white">
                            <tr>
                                @foreach($report['headers'] as $header)
                                    <th>{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($report['rows'] as $row)
                            <tr>
                                @foreach($row as $cell)
                                    <td>
                                        @php
                                            $tag = match ((string) $cell) {
                                                'Completed', 'Resolved', 'Approved'      => 'tag-good',
                                                'On track', 'Under review', 'Action taken' => 'tag-warn',
                                                'Needs follow-up', 'Pending'             => 'tag-critical',
                                                'Not started', 'Declined'                => 'tag-muted',
                                                default                                  => null,
                                            };
                                        @endphp
                                        @if($tag)
                                            <span class="tag {{ $tag }}">{{ $cell }}</span>
                                        @else
                                            {{ $cell }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endsection
