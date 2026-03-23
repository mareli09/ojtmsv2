@extends('layouts.admin')

@section('title', 'Archived Users')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/users-archive" class="nav-link active"><i class="fas fa-archive"></i> Archived Users</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-archive"></i> Archived Users</h2>
        <a href="/admin/users" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Active Users
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
            <h5 class="mb-0"><i class="fas fa-trash"></i> Deleted Users</h5>
        </div>
        <div class="card-body">
            @if($users && $users->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Section</th>
                            <th>Deleted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td><strong>{{ $user->first_name . ' ' . $user->last_name }}</strong></td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @php
                                    $roleBgColor = $user->role === 'admin' ? '#e74c3c' : ($user->role === 'faculty' ? '#3498db' : '#27ae60');
                                @endphp
                                <span class="badge" style="background-color: {{ $roleBgColor }}; color: white;">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->section)
                                    <span class="badge" style="background-color: var(--ojtms-accent); color: var(--ojtms-primary);">{{ $user->section->name }}</span>
                                @else
                                    <em class="text-muted">--</em>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $user->deleted_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>
                                <form action="{{ route('users.restore', $user->id) }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" title="Restore">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox" style="font-size: 3rem; color: var(--ojtms-light); margin-bottom: 15px;"></i>
                <p class="text-muted">No archived users.</p>
                <a href="/admin/users" class="btn btn-primary mt-3">
                    <i class="fas fa-arrow-left"></i> Go to Users
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
@endsection
