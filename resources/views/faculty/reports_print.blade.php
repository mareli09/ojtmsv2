<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>OJTMS Report{{ count($reports) > 1 ? 's' : '' }} — {{ now()->format('Y-m-d') }}</title>
<style>
    :root { --navy:#000156; --gold:#f4b61b; --rule:#d9d9d4; --muted:#5f5e5a; }

    * { box-sizing: border-box; }
    body {
        margin: 0; padding: 28px 32px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #111; font-size: 12px; background: #fff;
    }

    .doc-head {
        display: flex; justify-content: space-between; align-items: flex-start;
        border-bottom: 3px solid var(--gold); padding-bottom: 12px; margin-bottom: 22px;
    }
    .doc-head h1 { margin: 0 0 4px; font-size: 20px; color: var(--navy); }
    .doc-head p  { margin: 0; font-size: 11px; color: var(--muted); }
    .doc-head .meta { text-align: right; font-size: 11px; color: var(--muted); line-height: 1.6; }

    .report { margin-bottom: 30px; }
    .report + .report { page-break-before: always; }

    .report h2 {
        font-size: 15px; color: var(--navy); margin: 0 0 4px;
        border-left: 4px solid var(--gold); padding-left: 10px;
    }
    .report .sub { font-size: 11px; color: var(--muted); margin: 0 0 12px 14px; }

    .summary { margin: 0 0 12px 14px; font-size: 11px; color: var(--muted); }
    .summary span { margin-right: 22px; }
    .summary strong { color: var(--navy); font-size: 14px; }

    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 6px 8px; text-align: left; border-bottom: 1px solid var(--rule); vertical-align: top; }
    th {
        background: #f2f2f0; color: var(--navy); font-size: 10px;
        text-transform: uppercase; letter-spacing: .03em; border-bottom: 2px solid var(--navy);
    }
    td { font-size: 11px; font-variant-numeric: tabular-nums; }
    tbody tr:nth-child(even) td { background: #fafaf9; }

    .empty { padding: 16px; font-size: 11px; color: var(--muted); font-style: italic; }

    .signature {
        margin-top: 34px; display: flex; gap: 60px; font-size: 11px; color: var(--muted);
    }
    .signature div { flex: 1; }
    .signature .line { border-top: 1px solid #333; margin-top: 38px; padding-top: 4px; color: #111; }

    .print-bar {
        position: fixed; top: 12px; right: 16px;
        background: var(--navy); color: var(--gold); border: none;
        padding: 9px 18px; border-radius: 5px; font-weight: 600; cursor: pointer; font-size: 12px;
    }

    @media print {
        body { padding: 0; }
        .print-bar { display: none; }
        thead { display: table-header-group; }
        tr { page-break-inside: avoid; }
    }
</style>
</head>
<body>

<button class="print-bar" onclick="window.print()">Print / Save as PDF</button>

<div class="doc-head">
    <div>
        <h1>OJT Monitoring System</h1>
        <p>Faculty Report{{ count($reports) > 1 ? ' Pack' : '' }}</p>
    </div>
    <div class="meta">
        <strong>{{ $user->first_name }} {{ $user->last_name }}</strong><br>
        {{ $user->department ?? 'Faculty' }}<br>
        Generated {{ now()->format('F d, Y g:i A') }}
    </div>
</div>

@foreach($reports as $report)
<div class="report">
    <h2>{{ $report['title'] }}</h2>
    <p class="sub">{{ $report['subtitle'] }}</p>

    @if($report['summary'])
    <div class="summary">
        @foreach($report['summary'] as $label => $value)
            <span><strong>{{ $value }}</strong> {{ $label }}</span>
        @endforeach
    </div>
    @endif

    @if(empty($report['rows']))
        <p class="empty">{{ $report['empty'] }}</p>
    @else
        <table>
            <thead>
                <tr>@foreach($report['headers'] as $header)<th>{{ $header }}</th>@endforeach</tr>
            </thead>
            <tbody>
                @foreach($report['rows'] as $row)
                <tr>@foreach($row as $cell)<td>{{ $cell }}</td>@endforeach</tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endforeach

<div class="signature">
    <div>
        Prepared by
        <div class="line">{{ $user->first_name }} {{ $user->last_name }} &mdash; Faculty</div>
    </div>
    <div>
        Noted by
        <div class="line">OJT Coordinator</div>
    </div>
</div>

</body>
</html>
