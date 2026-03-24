@extends('layouts.student')

@section('title', 'My OJT Checklist')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-clipboard-list"></i> My OJT Checklist</h2>
    <p class="text-muted mb-4">Complete each step in order. The next step unlocks only after your faculty approves the current one.</p>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="checklist-steps">
        @foreach($checklistItems as $i => $item)
        @php
            $entry   = $entryByItem[$item['label']] ?? null;
            $isLocked = $locked[$item['label']];
            $status  = $entry ? $entry->faculty_status : null;
            $submitted = $entry && $entry->student_submitted_at;

            // Visual state
            if ($isLocked) {
                $stepClass = 'step-locked';
                $badgeColor = 'secondary';
                $badgeText  = 'Locked';
                $stepIcon   = 'fas fa-lock';
            } elseif ($status === 'approved') {
                $stepClass = 'step-approved';
                $badgeColor = 'success';
                $badgeText  = 'Approved';
                $stepIcon   = 'fas fa-check-circle';
            } elseif ($status === 'declined') {
                $stepClass = 'step-declined';
                $badgeColor = 'danger';
                $badgeText  = 'Declined — Resubmit';
                $stepIcon   = 'fas fa-times-circle';
            } elseif ($submitted) {
                $stepClass = 'step-pending';
                $badgeColor = 'warning';
                $badgeText  = 'Pending Review';
                $stepIcon   = 'fas fa-clock';
            } else {
                $stepClass = 'step-open';
                $badgeColor = 'primary';
                $badgeText  = 'Not Submitted';
                $stepIcon   = 'fas fa-circle-dot';
            }

            // Determine action URL
            $submitItems = ['Registration card','Medical Record','Receipt of OJT Kit','Waiver','Endorsement letter','MOA'];
            if (in_array($item['label'], $submitItems)) {
                $actionUrl = '/student/checklist/' . urlencode($item['label']) . '/submit';
            } else {
                $actionUrl = $item['url'];
            }

            // Remarks to show if declined
            $remarks = null;
            if ($entry && $status === 'declined') {
                $remarks = $entry->faculty_coc_remarks
                    ?? $entry->faculty_supervisor_eval_remarks
                    ?? $entry->faculty_appraisal_remarks
                    ?? $entry->faculty_weekly_remarks
                    ?? $entry->faculty_remarks;
            }
        @endphp

        <div class="step-row {{ $stepClass }}">
            <div class="step-number">
                <div class="step-circle {{ $stepClass }}">
                    @if($status === 'approved')
                        <i class="fas fa-check"></i>
                    @elseif($isLocked)
                        <i class="fas fa-lock"></i>
                    @elseif($status === 'declined')
                        <i class="fas fa-times"></i>
                    @else
                        {{ $i + 1 }}
                    @endif
                </div>
                @if(!$loop->last)
                <div class="step-line {{ $status === 'approved' ? 'line-done' : '' }}"></div>
                @endif
            </div>

            <div class="step-content">
                <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                    <i class="{{ $item['icon'] }}" style="color:var(--ojtms-accent); font-size:1.1rem;"></i>
                    <strong style="font-size:1rem;">{{ $item['label'] }}</strong>
                    <span class="badge bg-{{ $badgeColor }}">{{ $badgeText }}</span>
                    @if($submitted && !$isLocked)
                    <small class="text-muted">Submitted {{ $entry->student_submitted_at->format('M d, Y') }}</small>
                    @endif
                </div>
                <p class="text-muted small mb-2">{{ $item['description'] }}</p>

                @if($remarks)
                <div class="alert alert-danger py-1 px-2 mb-2" style="font-size:12px;">
                    <i class="fas fa-comment-dots"></i> <em>Faculty: {{ $remarks }}</em>
                </div>
                @endif

                @if(!$isLocked)
                    @if($status === 'approved')
                    <a href="{{ $actionUrl }}" class="btn btn-sm btn-outline-success">
                        <i class="fas fa-eye"></i> View
                    </a>
                    @else
                    <a href="{{ $actionUrl }}" class="btn btn-sm btn-primary">
                        @if($status === 'declined')
                            <i class="fas fa-redo"></i> Resubmit
                        @elseif($submitted)
                            <i class="fas fa-edit"></i> Update
                        @else
                            <i class="fas fa-arrow-right"></i> Submit
                        @endif
                    </a>
                    @endif
                @else
                <button class="btn btn-sm btn-secondary" disabled>
                    <i class="fas fa-lock"></i> Complete step {{ $i }} first
                </button>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<style>
.checklist-steps {
    max-width: 760px;
}
.step-row {
    display: flex;
    gap: 20px;
    margin-bottom: 0;
}
.step-number {
    display: flex;
    flex-direction: column;
    align-items: center;
    flex-shrink: 0;
    width: 44px;
}
.step-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
    border: 3px solid transparent;
    flex-shrink: 0;
}
.step-circle.step-approved  { background: #d1fae5; border-color: #10b981; color: #065f46; }
.step-circle.step-declined  { background: #fee2e2; border-color: #ef4444; color: #991b1b; }
.step-circle.step-pending   { background: #fef3c7; border-color: #f59e0b; color: #92400e; }
.step-circle.step-open      { background: #eff6ff; border-color: #3b82f6; color: #1e40af; }
.step-circle.step-locked    { background: #f3f4f6; border-color: #d1d5db; color: #9ca3af; }

.step-line {
    width: 3px;
    flex: 1;
    min-height: 28px;
    background: #e5e7eb;
    margin: 4px 0;
}
.step-line.line-done { background: #10b981; }

.step-content {
    flex: 1;
    padding: 10px 16px 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
    border-left: 4px solid #e5e7eb;
    margin-bottom: 8px;
}
.step-approved .step-content  { border-left-color: #10b981; }
.step-declined .step-content  { border-left-color: #ef4444; }
.step-pending .step-content   { border-left-color: #f59e0b; }
.step-open .step-content      { border-left-color: #3b82f6; }
.step-locked .step-content    { border-left-color: #d1d5db; opacity: 0.7; }
</style>
@endsection
