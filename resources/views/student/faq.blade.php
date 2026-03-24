@extends('layouts.student')

@section('title', 'FAQ')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
    <p class="text-muted">Find answers to common questions about OJT requirements and the system.</p>

    {{-- Search --}}
    <div class="mb-4">
        <div class="input-group" style="max-width:480px">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="faqSearch" class="form-control" placeholder="Search questions or answers...">
            <button class="btn btn-outline-secondary" type="button" id="clearSearch" style="display:none">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    @php
        $grouped = $faqs->groupBy('category');
    @endphp

    <div id="noResults" class="alert alert-info" style="display:none">
        <i class="fas fa-info-circle me-2"></i>No FAQs match your search.
    </div>

    @if($faqs->count() == 0)
    <div class="alert alert-info">
        <i class="fas fa-info-circle me-2"></i>No FAQs have been posted yet. Check back later.
    </div>
    @else
    @foreach($grouped as $category => $items)
    <div class="faq-category mb-4">
        <h5 class="text-muted fw-semibold mb-2"><i class="fas fa-tag me-2"></i>{{ $category }}</h5>
        <div class="accordion" id="accordion_{{ Str::slug($category) }}">
            @foreach($items as $i => $faq)
            <div class="accordion-item border-0 mb-2 rounded shadow-sm faq-item" data-q="{{ strtolower($faq->question) }}" data-a="{{ strtolower($faq->answer) }}">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed rounded" type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#faq_{{ $faq->id }}">
                        <i class="fas fa-question-circle me-2" style="color: var(--ojtms-accent);"></i>
                        <span class="faq-question-text">{{ $faq->question }}</span>
                    </button>
                </h2>
                <div id="faq_{{ $faq->id }}" class="accordion-collapse collapse"
                    data-bs-parent="#accordion_{{ Str::slug($category) }}">
                    <div class="accordion-body text-muted" style="white-space: pre-wrap;">
                        <span class="faq-answer-text">{{ $faq->answer }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
    @endif
</div>

<script>
const searchInput = document.getElementById('faqSearch');
const clearBtn    = document.getElementById('clearSearch');
const noResults   = document.getElementById('noResults');

searchInput.addEventListener('input', function() {
    const q = this.value.trim().toLowerCase();
    clearBtn.style.display = q ? '' : 'none';

    let anyVisible = false;

    document.querySelectorAll('.faq-item').forEach(function(item) {
        const question = item.dataset.q || '';
        const answer   = item.dataset.a || '';
        const match    = !q || question.includes(q) || answer.includes(q);
        item.style.display = match ? '' : 'none';
        if (match) anyVisible = true;

        // Highlight match and auto-expand if searching
        if (q && match) {
            const collapseEl = item.querySelector('.accordion-collapse');
            if (collapseEl && !collapseEl.classList.contains('show')) {
                new bootstrap.Collapse(collapseEl, { toggle: false }).show();
            }
        } else if (!q) {
            const collapseEl = item.querySelector('.accordion-collapse');
            if (collapseEl && collapseEl.classList.contains('show')) {
                new bootstrap.Collapse(collapseEl, { toggle: false }).hide();
            }
        }
    });

    // Show/hide category headings
    document.querySelectorAll('.faq-category').forEach(function(cat) {
        const visible = [...cat.querySelectorAll('.faq-item')].some(i => i.style.display !== 'none');
        cat.style.display = visible ? '' : 'none';
    });

    noResults.style.display = (!anyVisible && q) ? '' : 'none';
});

clearBtn.addEventListener('click', function() {
    searchInput.value = '';
    searchInput.dispatchEvent(new Event('input'));
    searchInput.focus();
});
</script>
@endsection
