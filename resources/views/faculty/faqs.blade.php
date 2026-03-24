@extends('layouts.admin')

@section('title', 'FAQ Management')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link active"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0"><i class="fas fa-question-circle"></i> FAQ Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus me-1"></i> Add FAQ
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Active FAQs --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-list me-2"></i>Active FAQs ({{ $faqs->count() }})</span>
            <input type="text" id="faqSearch" class="form-control form-control-sm w-auto" placeholder="Search FAQs...">
        </div>
        <div class="card-body p-0">
            @if($faqs->count() == 0)
            <p class="text-muted p-4 mb-0">No FAQs yet. Click "Add FAQ" to create one.</p>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="faqTable">
                    <thead class="table-light">
                        <tr>
                            <th style="width:140px">Category</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th style="width:160px">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($faqs as $faq)
                        <tr>
                            <td><span class="badge bg-secondary">{{ $faq->category }}</span></td>
                            <td>{{ $faq->question }}</td>
                            <td class="text-muted">{{ Str::limit($faq->answer, 80) }}</td>
                            <td>
                                <div class="d-flex gap-1 flex-wrap">
                                    <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#viewModal{{ $faq->id }}" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $faq->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="/faculty/faqs/{{ $faq->id }}" class="d-inline"
                                        onsubmit="return confirm('Archive this FAQ? You can restore it later.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Archived FAQs --}}
    @if($archived->count() > 0)
    <div class="mb-2">
        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#archivedSection">
            <i class="fas fa-archive me-1"></i>Archived FAQs ({{ $archived->count() }})
        </button>
    </div>
    <div class="collapse" id="archivedSection">
        <div class="card border-secondary">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-secondary">
                            <tr>
                                <th style="width:140px">Category</th>
                                <th>Question</th>
                                <th>Answer</th>
                                <th style="width:180px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archived as $faq)
                            <tr class="text-muted">
                                <td><span class="badge bg-secondary">{{ $faq->category }}</span></td>
                                <td>{{ $faq->question }}</td>
                                <td>{{ Str::limit($faq->answer, 80) }}</td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        <form method="POST" action="/faculty/faqs/{{ $faq->id }}/restore" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success" title="Restore">
                                                <i class="fas fa-undo"></i> Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="/faculty/faqs/{{ $faq->id }}/force" class="d-inline"
                                            onsubmit="return confirm('Permanently delete this FAQ? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete permanently">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add New FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/faculty/faqs">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Category</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" list="categoryList"
                            placeholder="e.g., DTR & Attendance, Documents, General" required>
                        <datalist id="categoryList">
                            <option value="General">
                            <option value="DTR & Attendance">
                            <option value="Documents & Submissions">
                            <option value="Reviews & Status">
                            <option value="Account & Profile">
                            <option value="Incident Reports">
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Question</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control" placeholder="Enter the question..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Answer</strong> <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="5" placeholder="Enter the answer..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save FAQ</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- View & Edit Modals --}}
@foreach($faqs as $faq)
{{-- View Modal --}}
<div class="modal fade" id="viewModal{{ $faq->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>View FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-1"><span class="badge bg-secondary">{{ $faq->category }}</span></p>
                <h6 class="mt-2">Question:</h6>
                <div class="bg-light p-3 rounded mb-3">{{ $faq->question }}</div>
                <h6>Answer:</h6>
                <div class="bg-light p-3 rounded" style="white-space: pre-wrap;">{{ $faq->answer }}</div>
                <small class="text-muted d-block mt-2">Added: {{ $faq->created_at->format('M d, Y g:i A') }}</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $faq->id }}" data-bs-dismiss="modal">
                    <i class="fas fa-edit me-1"></i>Edit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal{{ $faq->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit FAQ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/faculty/faqs/{{ $faq->id }}">
                @csrf @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label"><strong>Category</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="category" class="form-control" list="categoryList"
                            value="{{ $faq->category }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Question</strong> <span class="text-danger">*</span></label>
                        <input type="text" name="question" class="form-control" value="{{ $faq->question }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label"><strong>Answer</strong> <span class="text-danger">*</span></label>
                        <textarea name="answer" class="form-control" rows="5" required>{{ $faq->answer }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
document.getElementById('faqSearch').addEventListener('input', function() {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#faqTable tbody tr').forEach(function(row) {
        row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endsection
