@extends('layouts.admin')

@section('title', 'Archived Sections')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/sections-archive" class="nav-link active"><i class="fas fa-archive"></i> Archived Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-archive"></i> Archived Sections</h2>
        <a href="/admin/sections" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Active Sections
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-trash"></i> Deleted Sections</h5>
        </div>
        <div class="card-body">
            @if($sections && count($sections) > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="archiveTable">
                    <thead>
                        <tr>
                            <th>Section Name</th>
                            <th>School Year</th>
                            <th>Term</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Faculty</th>
                            <th>Deleted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                        <tr>
                            <td>
                                <strong>{{ $section->name ?? 'A1' }}</strong>
                            </td>
                            <td>{{ $section->school_year ?? '2025-2026' }}</td>
                            <td>
                                <span class="badge" style="background-color: var(--ojtms-accent); color: var(--ojtms-primary);">
                                    {{ $section->term ?? 'Term 1' }}
                                </span>
                            </td>
                            <td>
                                <small>
                                    {{ $section->day ?? 'Monday' }}<br>
                                    {{ $section->start_time ?? '08:00' }} - {{ $section->end_time ?? '12:00' }}
                                </small>
                            </td>
                            <td>{{ $section->room ?? 'Room 101' }}</td>
                            <td>
                                @php
                                    $facultyNames = [
                                        1 => 'Dr. Juan Dela Cruz',
                                        2 => 'Prof. Maria Santos',
                                        3 => 'Engr. Carlos Lopez'
                                    ];
                                @endphp
                                @if($section->faculty_id && isset($facultyNames[$section->faculty_id]))
                                    <span class="badge" style="background-color: var(--ojtms-primary); color: white;">
                                        {{ $facultyNames[$section->faculty_id] }}
                                    </span>
                                @else
                                    <em class="text-muted">--</em>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $section->deleted_at->format('M d, Y H:i') ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <form action="/admin/sections/{{ $section->id }}/restore" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-danger" onclick="confirmPermanentDelete('{{ $section->id }}')" title="Permanent Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--ojtms-light); margin-bottom: 15px;"></i>
                <p class="text-muted">No archived sections.</p>
                <a href="/admin/sections" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Go to Sections
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .table-hover tbody tr {
        transition: all 0.3s;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 1, 86, 0.05);
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }
</style>

<script>
    function confirmPermanentDelete(sectionId) {
        if (confirm('⚠️ This will permanently delete this section. This action cannot be undone. Are you sure?')) {
            // For permanent delete, we would need a separate route
            // For now, inform user
            alert('Permanent delete feature will be implemented soon.');
        }
    }
</script>
@endsection
