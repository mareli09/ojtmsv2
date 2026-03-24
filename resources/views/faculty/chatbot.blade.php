@extends('layouts.admin')

@section('title', 'AI Chatbot')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link active"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-robot"></i> AI Chatbot</h2>
    <p class="text-muted">Ask anything about OJT management, student performance, review guidance, and best practices.</p>

    @if(!$aiEnabled)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>AI features are disabled.</strong> The administrator has turned off the OpenAI integration. Contact your admin to enable it.
    </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card" style="height: 620px; display: flex; flex-direction: column;">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="fas fa-robot" style="color: var(--ojtms-accent);"></i>
                    <span>OJT Faculty Assistant</span>
                    @if($aiEnabled)
                    <span class="badge bg-success ms-auto"><i class="fas fa-circle me-1" style="font-size:8px"></i>Powered by OpenAI</span>
                    @else
                    <span class="badge bg-secondary ms-auto"><i class="fas fa-circle me-1" style="font-size:8px"></i>Offline</span>
                    @endif
                </div>

                <div id="chatMessages" style="flex:1; overflow-y:auto; padding:20px; background:#f9f9f9;">
                    <div class="d-flex gap-2 mb-3">
                        <div class="chat-avatar bot-avatar"><i class="fas fa-robot"></i></div>
                        <div class="chat-bubble bot-bubble">
                            <p class="mb-0">
                                Hello, {{ $user->first_name }}! I'm your AI OJT assistant. I can help you with:
                            </p>
                            <ul class="mb-0 mt-1" style="font-size:13px">
                                <li>Student performance analysis & review priorities</li>
                                <li>Checklist & submission guidance</li>
                                <li>OJT policies & best practices</li>
                                <li>Incident report handling advice</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div style="padding:15px;border-top:1px solid #eee;background:white;">
                    <form id="chatForm" class="d-flex gap-2">
                        <input type="text" id="chatInput" class="form-control"
                            placeholder="Ask me anything about OJT management..."
                            autocomplete="off" required {{ !$aiEnabled ? 'disabled' : '' }}>
                        <button type="submit" class="btn btn-primary px-4" {{ !$aiEnabled ? 'disabled' : '' }}>
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                    <div class="d-flex justify-content-between mt-1">
                        <small class="text-muted">Powered by OpenAI GPT — responses are for guidance only.</small>
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
let history = [];

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
        .replace(/^# (.*$)/gm, '<h5 class="mt-2 mb-1">$1</h5>')
        .replace(/^- (.*$)/gm, '<li>$1</li>')
        .replace(/(<li>.*<\/li>)/s, '<ul class="mb-1">$1</ul>')
        .replace(/\n/g, '<br>');
}

chatForm.addEventListener('submit', async function(e) {
    e.preventDefault();
    const msg = chatInput.value.trim();
    if (!msg) return;

    addMessage(msg, true);
    history.push({ role: 'user', content: msg });
    chatInput.value = '';
    chatInput.disabled = true;

    showTyping();

    try {
        const res = await fetch('/faculty/chatbot/ask', {
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
    addMessage(`Hello, {{ $user->first_name }}! Chat cleared. How can I help you?`, false);
}
</script>
@endsection
