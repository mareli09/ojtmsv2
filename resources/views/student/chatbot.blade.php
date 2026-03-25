@extends('layouts.student')

@section('title', 'Chatbot AI')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-robot"></i> Chatbot AI</h2>
    <p class="text-muted">Ask anything about your OJT — checklist, submissions, DTR, requirements, and more.</p>

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card" style="height: 620px; display: flex; flex-direction: column;">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-robot" style="color: var(--ojtms-accent);"></i>
                    <span>OJT Assistant</span>
                    @if($aiEnabled)
                    <span class="badge bg-success ms-auto"><i class="fas fa-circle me-1" style="font-size:8px"></i>Powered by OpenAI</span>
                    @else
                    <span class="badge bg-warning text-dark ms-auto"><i class="fas fa-circle me-1" style="font-size:8px"></i>Basic Mode</span>
                    @endif
                </div>

                <div id="chatMessages" style="flex:1; overflow-y:auto; padding:20px; background:#f9f9f9;">
                    <div class="d-flex gap-2 mb-3">
                        <div class="chat-avatar bot-avatar"><i class="fas fa-robot"></i></div>
                        <div class="chat-bubble bot-bubble">
                            <p class="mb-0">
                                Hi, {{ $user->first_name }}! I'm your OJT Assistant. I can help you with:
                            </p>
                            <ul class="mb-0 mt-1" style="font-size:13px">
                                <li>Checklist progress & submission steps</li>
                                <li>DTR, weekly reports, monthly appraisals</li>
                                <li>OJT requirements & policies</li>
                                <li>Incident reports & profile settings</li>
                            </ul>
                            @if(!$aiEnabled)
                            <hr class="my-2">
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i>Running in basic mode (keyword matching). AI mode is disabled by the administrator.</small>
                            @endif
                        </div>
                    </div>
                </div>

                <div style="padding:15px;border-top:1px solid #eee;background:white;">
                    <form id="chatForm" class="d-flex gap-2">
                        <input type="text" id="chatInput" class="form-control"
                            placeholder="Ask me about your OJT..."
                            autocomplete="off" required>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">{{ $aiEnabled ? 'Powered by OpenAI GPT — ' : '' }}Responses are for guidance only.</small>
                        <button class="btn btn-sm btn-link text-muted p-0" onclick="clearChat()"><i class="fas fa-broom me-1"></i>Clear</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-avatar { width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:14px;color:white; }
.bot-avatar  { background:var(--ojtms-primary); }
.user-avatar { background:#27ae60; }
.chat-bubble { border-radius:12px;padding:10px 14px;max-width:80%;box-shadow:0 1px 4px rgba(0,0,0,0.08);font-size:14px;line-height:1.5; }
.bot-bubble  { background:white;border-top-left-radius:2px; }
.user-bubble { background:var(--ojtms-primary);color:white;border-top-right-radius:2px; }
.typing-indicator span { display:inline-block;width:8px;height:8px;border-radius:50%;background:#999;margin:0 2px;animation:typing 1.2s infinite; }
.typing-indicator span:nth-child(2) { animation-delay:0.2s; }
.typing-indicator span:nth-child(3) { animation-delay:0.4s; }
@keyframes typing { 0%,60%,100%{transform:translateY(0)} 30%{transform:translateY(-6px)} }
</style>

<script>
const chatMessages = document.getElementById('chatMessages');
const chatForm     = document.getElementById('chatForm');
const chatInput    = document.getElementById('chatInput');
const aiEnabled    = {{ $aiEnabled ? 'true' : 'false' }};
let history = [];

// Keyword fallback for basic mode
const faqs = {
    'dtr':              'To submit your DTR, go to <strong>Daily Time Record</strong> in the sidebar, then click <em>Submit New DTR</em>. Fill in your week, hours, and supervisor validation.',
    'weekly report':    'Submit your weekly report via <strong>Weekly Reports</strong> in the sidebar. Click "Submit New Weekly Report" and include your task description.',
    'monthly appraisal':'For monthly appraisal, go to <strong>Monthly Appraisal</strong> in the sidebar. Include your grade/rating and the appraisal file.',
    'supervisor eval':  'Submit supervisor evaluation via <strong>Supervisor Evaluation</strong> in the sidebar. Upload the signed evaluation form and enter your grade.',
    'coc':              'Certificate of Completion is submitted via <strong>Supervisor Evaluation → Certificate of Completion</strong>.',
    'certificate':      'Certificate of Completion — go to the Checklist and submit via the Certificate of Completion section.',
    'endorsement':      'The Endorsement Letter is part of your checklist. Ask your faculty coordinator for the letter and submit it via the Checklist.',
    'moa':              'The MOA (Memorandum of Agreement) may require multiple signings. Submit each version via the Checklist.',
    'declined':         'If your submission was declined, check the <strong>faculty remarks</strong> on that checklist item. Correct the issue and resubmit.',
    'password':         'You can change your password under <strong>Profile Settings</strong> in the sidebar.',
    'profile':          'Update your email, contact, and password under <strong>Profile Settings</strong> in the sidebar.',
    'announcement':     'Faculty announcements are visible under <strong>Announcements</strong> in the sidebar.',
    'incident':         'To report an incident, go to <strong>Incident Report</strong> in the sidebar and fill out the form. You can attach evidence files.',
    'hours':            'Your total OJT hours are tracked in the DTR section. Your faculty sets the required target hours. Check your Dashboard for the progress bar.',
    'requirement':      'OJT checklist requirements: Medical Record, OJT Kit Receipt, Waiver, Endorsement Letter, MOA, DTR, Weekly Reports, Monthly Appraisals, Supervisor Evaluation, and Certificate of Completion.',
    'checklist':        'Your checklist is accessible via <strong>Checklist</strong> in the sidebar. It shows all required OJT documents and their approval status.',
    'faq':              'Check the <strong>FAQ</strong> section in the sidebar for answers to frequently asked questions about OJT.',
    'hello':            'Hello! How can I assist you with your OJT today?',
    'hi':               'Hi there! Ask me anything about your OJT requirements or submissions.',
    'help':             'I can help with: DTR, weekly reports, monthly appraisals, supervisor evaluation, certificate of completion, checklist status, profile updates, incident reports, and more!',
    'status':           'Check your <strong>Dashboard</strong> to see your overall checklist progress, OJT hours, and submission statuses.',
    'progress':         'Your checklist progress and DTR hours are displayed on your <strong>Dashboard</strong>. Go there to see a complete overview.',
    'submit':           'To submit any document, go to the corresponding section in the sidebar (e.g., DTR, Weekly Report, Monthly Appraisal) and click the "Submit" button.',
};

function addMessage(html, isUser) {
    const wrapper = document.createElement('div');
    wrapper.className = 'd-flex gap-2 mb-3' + (isUser ? ' flex-row-reverse' : '');
    wrapper.innerHTML = `
        <div class="chat-avatar ${isUser ? 'user-avatar' : 'bot-avatar'}">
            <i class="fas fa-${isUser ? 'user' : 'robot'}"></i>
        </div>
        <div class="chat-bubble ${isUser ? 'user-bubble' : 'bot-bubble'}">${html}</div>
    `;
    chatMessages.appendChild(wrapper);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return wrapper;
}

function showTyping() {
    const el = addMessage('<div class="typing-indicator"><span></span><span></span><span></span></div>', false);
    el.id = 'typingIndicator';
    return el;
}

function removeTyping() {
    const el = document.getElementById('typingIndicator');
    if (el) el.remove();
}

function formatMarkdown(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/^### (.*$)/gm, '<h6 class="mt-2 mb-1">$1</h6>')
        .replace(/^## (.*$)/gm, '<h6 class="mt-2 mb-1"><strong>$1</strong></h6>')
        .replace(/^- (.*$)/gm, '&bull; $1<br>')
        .replace(/\n/g, '<br>');
}

function getKeywordReply(input) {
    const lower = input.toLowerCase();
    for (const [key, reply] of Object.entries(faqs)) {
        if (lower.includes(key)) return reply;
    }
    return "I'm not sure about that. You can check the <strong>FAQ</strong> section or contact your faculty directly. Try rephrasing your question!";
}

chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const msg = chatInput.value.trim();
    if (!msg) return;

    addMessage(msg, true);
    chatInput.value = '';

    if (!aiEnabled) {
        // Basic keyword mode
        setTimeout(() => addMessage(getKeywordReply(msg), false), 500);
        return;
    }

    // OpenAI mode
    history.push({ role: 'user', content: msg });
    chatInput.disabled = true;
    showTyping();

    try {
        const res = await fetch('/student/chatbot/ask', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ message: msg, history: history.slice(-10) })
        });
        const data = await res.json();
        removeTyping();
        addMessage(formatMarkdown(data.reply), false);
        history.push({ role: 'assistant', content: data.reply });
    } catch(err) {
        removeTyping();
        addMessage('<span class="text-danger">Failed to connect. Please try again.</span>', false);
    }

    chatInput.disabled = false;
    chatInput.focus();
});

function clearChat() {
    history = [];
    chatMessages.innerHTML = '';
    addMessage(`Hi, {{ $user->first_name }}! Chat cleared. How can I help you with your OJT?`, false);
}
</script>
@endsection
