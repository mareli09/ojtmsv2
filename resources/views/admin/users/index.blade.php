@extends('layouts.admin')

@section('title', 'Manage Users')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link active"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-users"></i> User Management</h2>
        <div>
            <a href="/admin/users-archive" class="btn btn-secondary me-2">
                <i class="fas fa-archive"></i> Archived Users
            </a>
            <a href="/admin/users/bulk-import" class="btn btn-info me-2">
                <i class="fas fa-upload"></i> Bulk Import
            </a>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New User
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Search and Filter Card -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}">
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, username, email..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="role" class="form-select">
                            <option value="">All Roles</option>
                            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="section" class="form-select">
                            <option value="">All Sections</option>
                            @foreach($sections as $section)
                            <option value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> Search
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Users</h5>
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
                            <th>Status</th>
                            <th>Last Activity</th>
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
                                    @if($user->role === 'faculty')
                                        <br>
                                        <small class="text-success mt-1" style="display: inline-block;">
                                            <i class="fas fa-check-circle"></i> Faculty Assigned
                                        </small>
                                    @endif
                                @else
                                    @if($user->role === 'faculty')
                                        <small class="text-warning">
                                            <i class="fas fa-times-circle"></i> No Assignment
                                        </small>
                                    @else
                                        <em class="text-muted">--</em>
                                    @endif
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColor = $user->status === 'active' ? '#27ae60' : '#e74c3c';
                                @endphp
                                <span class="badge" style="background-color: {{ $statusColor }}; color: white;">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    {{ $user->last_activity_at ? $user->last_activity_at->format('M d, Y H:i') : 'Never' }}
                                </small>
                            </td>
                            <td>
                                <a href="{{ route('users.view', $user->id) }}" class="btn btn-sm btn-info" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display: inline;" onsubmit="return confirm('Archive this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Archive">
                                        <i class="fas fa-trash"></i>
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
                <p class="text-muted">No users found.</p>
                <a href="{{ route('users.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Add First User
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
