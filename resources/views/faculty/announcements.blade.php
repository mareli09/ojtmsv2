@extends('layouts.admin')

@section('title', 'Announcements')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link active"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-bullhorn"></i> Announcements</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
            <i class="fas fa-plus"></i> New Announcement
        </button>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    {{-- Stats --}}
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card text-center py-3">
                <div class="fs-2 fw-bold text-primary">{{ $active->count() }}</div>
                <div class="text-muted small">Active Announcements</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-center py-3">
                <div class="fs-2 fw-bold text-secondary">{{ $archived->count() }}</div>
                <div class="text-muted small">Archived</div>
            </div>
        </div>
    </div>

    {{-- Active Announcements --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-circle text-success me-2" style="font-size:10px;"></i> Active Announcements</span>
        </div>
        <div class="card-body p-0">
            @if($active->isEmpty())
            <div class="p-4 text-muted text-center">No active announcements. Click <strong>New Announcement</strong> to post one.</div>
            @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Headline</th>
                            <th>Posted By</th>
                            <th>Date Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($active as $i => $ann)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td><strong>{{ $ann->title }}</strong></td>
                            <td>{{ $ann->creator?->first_name }} {{ $ann->creator?->last_name }}</td>
                            <td>{{ $ann->created_at->format('M d, Y g:i A') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewModal{{ $ann->id }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#editModal{{ $ann->id }}">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <form method="POST" action="/faculty/announcements/{{ $ann->id }}/archive" class="d-inline"
                                    onsubmit="return confirm('Archive this announcement? Students will no longer see it.')">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-archive"></i> Archive
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- View Modal --}}
                        <div class="modal fade" id="viewModal{{ $ann->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>{{ $ann->title }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3 d-flex gap-3 text-muted small">
                                            <span><i class="fas fa-user me-1"></i>{{ $ann->creator?->first_name }} {{ $ann->creator?->last_name }}</span>
                                            <span><i class="fas fa-calendar me-1"></i>{{ $ann->created_at->format('F d, Y g:i A') }}</span>
                                            @if($ann->updated_at != $ann->created_at)
                                            <span><i class="fas fa-pencil-alt me-1"></i>Edited {{ $ann->updated_at->format('M d, Y g:i A') }}</span>
                                            @endif
                                        </div>
                                        <hr>
                                        <div style="white-space: pre-wrap; line-height: 1.7;">{{ $ann->content }}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Edit Modal --}}
                        <div class="modal fade" id="editModal{{ $ann->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Announcement</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="/faculty/announcements/{{ $ann->id }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Headline <span class="text-danger">*</span></label>
                                                <input type="text" name="title" class="form-control" value="{{ $ann->title }}" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                                                <textarea name="content" class="form-control" rows="6" required>{{ $ann->content }}</textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i>Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>
    </div>

    {{-- Archived Announcements --}}
    @if($archived->isNotEmpty())
    <div class="card">
        <div class="card-header">
            <i class="fas fa-archive me-2 text-secondary"></i> Archived Announcements
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Headline</th>
                            <th>Posted By</th>
                            <th>Date Posted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($archived as $i => $ann)
                        <tr class="text-muted">
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $ann->title }}</td>
                            <td>{{ $ann->creator?->first_name }} {{ $ann->creator?->last_name }}</td>
                            <td>{{ $ann->created_at->format('M d, Y') }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#viewArchivedModal{{ $ann->id }}">
                                    <i class="fas fa-eye"></i> View
                                </button>
                                <form method="POST" action="/faculty/announcements/{{ $ann->id }}/restore" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-success"><i class="fas fa-undo"></i> Restore</button>
                                </form>
                            </td>
                        </tr>

                        {{-- Archived View Modal --}}
                        <div class="modal fade" id="viewArchivedModal{{ $ann->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header bg-secondary text-white">
                                        <h5 class="modal-title"><i class="fas fa-archive me-2"></i>{{ $ann->title }} <span class="badge bg-light text-secondary ms-2">Archived</span></h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body text-muted">
                                        <div class="mb-3 d-flex gap-3 small">
                                            <span><i class="fas fa-user me-1"></i>{{ $ann->creator?->first_name }} {{ $ann->creator?->last_name }}</span>
                                            <span><i class="fas fa-calendar me-1"></i>{{ $ann->created_at->format('F d, Y g:i A') }}</span>
                                        </div>
                                        <hr>
                                        <div style="white-space: pre-wrap; line-height: 1.7;">{{ $ann->content }}</div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
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
                <h5 class="modal-title"><i class="fas fa-bullhorn me-2"></i>New Announcement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/faculty/announcements">
                @csrf
                <div class="modal-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                    </div>
                    @endif
                    <div class="mb-3">
                        <label class="form-label fw-bold">Headline <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="Enter announcement headline" value="{{ old('title') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Description <span class="text-danger">*</span></label>
                        <textarea name="content" class="form-control" rows="6" placeholder="Enter full announcement details..." required>{{ old('content') }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i>Publish</button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('createModal')).show();
    });
</script>
@endif
@endsection
