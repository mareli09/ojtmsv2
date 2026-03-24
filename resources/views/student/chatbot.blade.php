@extends('layouts.student')

@section('title', 'Chatbot AI')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-robot"></i> Chatbot AI</h2>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card" style="height: 600px; display: flex; flex-direction: column;">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-robot" style="color: var(--ojtms-accent);"></i>
                    <span>OJT Assistant</span>
                    <span class="badge bg-success ms-auto">Online</span>
                </div>

                <!-- Chat Messages -->
                <div id="chatMessages" style="flex:1; overflow-y:auto; padding: 20px; background: #f9f9f9;">
                    <div class="d-flex gap-2 mb-3">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--ojtms-primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="fas fa-robot text-white" style="font-size:14px;"></i>
                        </div>
                        <div style="background:white;border-radius:12px;border-top-left-radius:2px;padding:10px 14px;max-width:80%;box-shadow:0 1px 4px rgba(0,0,0,0.08);">
                            <p class="mb-0" style="font-size:14px;">
                                Hi! I'm your OJT Assistant. I can help you with questions about your checklist, submissions, requirements, and more. What can I help you with today?
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Input -->
                <div style="padding:15px;border-top:1px solid #eee;background:white;">
                    <form id="chatForm" class="d-flex gap-2">
                        <input type="text" id="chatInput" class="form-control"
                            placeholder="Ask me anything about your OJT..."
                            autocomplete="off" required>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <small class="text-muted mt-1 d-block">Powered by AI — responses are for guidance only.</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const chatMessages = document.getElementById('chatMessages');
const chatForm     = document.getElementById('chatForm');
const chatInput    = document.getElementById('chatInput');

const faqs = {
    'dtr':              'To submit your DTR, go to <strong>Checklist → Daily Time Record</strong>, then click <em>Submit New DTR</em>. Fill in your week, hours, and supervisor validation.',
    'weekly report':    'Submit your weekly report via <strong>Checklist → Weekly Report → Submit New Weekly Report</strong>. Include your task description and supervisor feedback.',
    'monthly appraisal':'For monthly appraisal, go to <strong>Checklist → Monthly Appraisal → Submit New Appraisal</strong>. Include your grade/rating and the appraisal file.',
    'supervisor eval':  'Submit supervisor evaluation via <strong>Checklist → Supervisor Evaluation → Submit Evaluation</strong>. Upload the signed evaluation form and enter your grade.',
    'coc':              'Certificate of Completion is submitted via <strong>Checklist → Certificate of Completion → Submit COC</strong>. Fill in company name, signed by, date issued, and upload the file.',
    'certificate':      'Certificate of Completion is submitted via <strong>Checklist → Certificate of Completion → Submit COC</strong>.',
    'endorsement':      'The Endorsement Letter is part of your checklist. Ask your faculty coordinator for the letter and submit it via the Checklist.',
    'moa':              'The MOA (Memorandum of Agreement) may require multiple signings. Submit each version via the Checklist → MOA section.',
    'declined':         'If your submission was declined, check the <strong>faculty remarks</strong> on that checklist item. Correct the issue and resubmit.',
    'password':         'You can change your password under <strong>Profile Settings</strong> in the sidebar.',
    'profile':          'Update your email, contact, and password under <strong>Profile Settings</strong> in the sidebar.',
    'announcement':     'Faculty announcements are visible under <strong>Announcements</strong> in the sidebar.',
    'incident':         'To report an incident, go to <strong>Incident Report</strong> in the sidebar and fill out the form.',
    'hours':            'Your total OJT hours are tracked in the DTR section. Your faculty sets the required target hours.',
    'requirement':      'OJT checklist requirements include: Medical Record, OJT Kit Receipt, Waiver, Endorsement Letter, MOA, DTR, Weekly Reports, Monthly Appraisals, Supervisor Evaluation, and Certificate of Completion.',
    'checklist':        'Your checklist is accessible via <strong>Checklist</strong> in the sidebar. It shows all required OJT documents and their approval status.',
    'hello':            'Hello! How can I assist you with your OJT today?',
    'hi':               'Hi there! Ask me anything about your OJT requirements or submissions.',
    'help':             'I can help with: DTR submission, weekly reports, monthly appraisals, supervisor evaluation, COC, checklist status, profile updates, and more. What do you need?',
};

function addMessage(text, isUser = false) {
    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex gap-2 mb-3' + (isUser ? ' flex-row-reverse' : '');

    const avatar = document.createElement('div');
    avatar.style.cssText = 'width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;';
    if (isUser) {
        avatar.style.background = '#27ae60';
        avatar.innerHTML = '<i class="fas fa-user text-white" style="font-size:14px;"></i>';
    } else {
        avatar.style.background = 'var(--ojtms-primary)';
        avatar.innerHTML = '<i class="fas fa-robot text-white" style="font-size:14px;"></i>';
    }

    const bubble = document.createElement('div');
    bubble.style.cssText = `background:${isUser ? 'var(--ojtms-primary)' : 'white'};color:${isUser ? 'white' : 'inherit'};border-radius:12px;${isUser ? 'border-top-right-radius:2px' : 'border-top-left-radius:2px'};padding:10px 14px;max-width:80%;box-shadow:0 1px 4px rgba(0,0,0,0.08);font-size:14px;`;
    bubble.innerHTML = text;

    wrapper.appendChild(avatar);
    wrapper.appendChild(bubble);
    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function getBotReply(input) {
    const lower = input.toLowerCase();
    for (const [key, reply] of Object.entries(faqs)) {
        if (lower.includes(key)) return reply;
    }
    return "I'm not sure about that. You can check the <strong>FAQ</strong> section or contact your faculty directly. You can also try rephrasing your question.";
}

chatForm.addEventListener('submit', function (e) {
    e.preventDefault();
    const msg = chatInput.value.trim();
    if (!msg) return;
    addMessage(msg, true);
    chatInput.value = '';

    setTimeout(() => {
        addMessage(getBotReply(msg), false);
    }, 600);
});
</script>
@endpush
