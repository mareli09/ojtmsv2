@extends('layouts.student')

@section('title', 'FAQ')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>

    @if(empty($faqs) || count($faqs) == 0)
    <div class="accordion" id="faqAccordion">
        @php
        $defaultFaqs = [
            ['q' => 'How do I submit my DTR?', 'a' => 'Go to Checklist → Daily Time Record → Submit New DTR. Fill in your week, hours worked, and supervisor validation, then upload your DTR file.'],
            ['q' => 'What documents do I need for my OJT checklist?', 'a' => 'You need: Medical Record, Receipt of OJT Kit, Waiver, Endorsement Letter, MOA, DTR (weekly), Weekly Report, Monthly Appraisal, Supervisor Evaluation, and Certificate of Completion.'],
            ['q' => 'How long does faculty review take?', 'a' => 'Reviews are typically processed within 1–3 working days. You will see the status updated on your checklist.'],
            ['q' => 'What do I do if my submission is declined?', 'a' => 'Check the faculty remarks on your submission for the reason. Correct the issue and resubmit.'],
            ['q' => 'How do I update my profile information?', 'a' => 'Go to Profile Settings from the sidebar. You can update your email, contact number, and password there.'],
            ['q' => 'Who do I contact for urgent concerns?', 'a' => 'Submit an Incident Report via the sidebar for urgent issues, or contact your assigned faculty directly.'],
        ];
        @endphp
        @foreach($defaultFaqs as $i => $faq)
        <div class="accordion-item border-0 mb-2 rounded shadow-sm">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} rounded" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                    <i class="fas fa-question-circle me-2" style="color: var(--ojtms-accent);"></i>
                    {{ $faq['q'] }}
                </button>
            </h2>
            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    {{ $faq['a'] }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="accordion" id="faqAccordion">
        @foreach($faqs as $i => $faq)
        <div class="accordion-item border-0 mb-2 rounded shadow-sm">
            <h2 class="accordion-header">
                <button class="accordion-button {{ $i > 0 ? 'collapsed' : '' }} rounded" type="button"
                    data-bs-toggle="collapse" data-bs-target="#faq{{ $i }}">
                    <i class="fas fa-question-circle me-2" style="color: var(--ojtms-accent);"></i>
                    {{ $faq->question }}
                </button>
            </h2>
            <div id="faq{{ $i }}" class="accordion-collapse collapse {{ $i == 0 ? 'show' : '' }}" data-bs-parent="#faqAccordion">
                <div class="accordion-body text-muted">
                    {{ $faq->answer }}
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
