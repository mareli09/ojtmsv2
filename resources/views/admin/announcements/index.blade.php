@extends('layouts.admin')

@section('title', 'Announcements')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link active"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-bullhorn"></i> Announcements</h2>
            <small class="text-muted">Manage all announcements for the landing page</small>
        </div>
        <a href="/admin/announcements/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Announcement
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Tabs for Active/Archived -->
    <ul class="nav nav-tabs mb-4" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#active" role="tab">
                <i class="fas fa-check-circle me-2"></i>Active Announcements
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#archived" role="tab">
                <i class="fas fa-archive me-2"></i>Archived
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Active Announcements Tab -->
        <div class="tab-pane fade show active" id="active" role="tabpanel">
            @if($activeAnnouncements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 35%;">Title</th>
                                <th style="width: 30%;">Content Preview</th>
                                <th style="width: 15%;">Created Date</th>
                                <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeAnnouncements as $announcement)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary-custom">{{ $announcement->title }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted">{{ Str::limit($announcement->content, 60) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <a href="/admin/announcements/{{ $announcement->id }}/edit" class="btn btn-sm btn-warning me-2" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/admin/announcements/{{ $announcement->id }}/archive" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-secondary" title="Archive" onclick="return confirm('Archive this announcement?')">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" style="background-color:var(--ojtms-light); border-color:var(--ojtms-primary);">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>No active announcements yet.</strong> <a href="/admin/announcements/create" class="alert-link">Create one now!</a>
                </div>
            @endif
        </div>

        <!-- Archived Announcements Tab -->
        <div class="tab-pane fade" id="archived" role="tabpanel">
            @if($archivedAnnouncements->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 35%;">Title</th>
                                <th style="width: 30%;">Content Preview</th>
                                <th style="width: 15%;">Created Date</th>
                                <th style="width: 15%;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($archivedAnnouncements as $announcement)
                            <tr class="table-secondary">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-muted">{{ $announcement->title }}</strong>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($announcement->content, 60) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $announcement->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <form action="/admin/announcements/{{ $announcement->id }}/restore" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Restore" onclick="return confirm('Restore this announcement?')">
                                            <i class="fas fa-undo"></i> Restore
                                        </button>
                                    </form>
                                    <form action="/admin/announcements/{{ $announcement->id }}/delete" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Permanently Delete" onclick="return confirm('Permanently delete this announcement? This cannot be undone!')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info text-center" style="background-color:var(--ojtms-light); border-color:var(--ojtms-primary);">
                    <i class="fas fa-inbox me-2"></i>
                    <strong>No archived announcements.</strong>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table th {
        background-color: var(--ojtms-light);
        color: var(--ojtms-primary);
        font-weight: 600;
        border-bottom: 2px solid var(--ojtms-accent);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9ff;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
        border: none;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 1, 86, 0.2);
    }

    .text-primary-custom {
        color: var(--ojtms-primary);
    }

    .nav-tabs .nav-link {
        color: var(--ojtms-primary);
        border-bottom: 2px solid transparent;
        transition: all 0.3s;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: var(--ojtms-accent);
        color: var(--ojtms-accent);
    }

    .nav-tabs .nav-link.active {
        border-bottom-color: var(--ojtms-accent);
        color: var(--ojtms-accent);
        background-color: transparent;
    }
</style>
@endsection
