@extends('layouts.admin')

@section('title', 'Faculty Assignment Audit')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-megaphone"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="fas fa-stethoscope"></i> Faculty Assignment Audit</h2>
            <small class="text-muted">Check and fix duplicate faculty assignments in sections</small>
        </div>
        <a href="/admin/sections" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Sections
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        {{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-warning">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        System Status: 
                        @if(count($duplicates) > 0)
                            <span class="badge bg-danger">{{ count($duplicates) }} Sections with Multiple Faculty</span>
                        @else
                            <span class="badge bg-success">All sections have valid faculty assignments</span>
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        <strong>One Faculty Per Section Rule:</strong> Each section should have only one faculty member assigned.
                        This audit tool helps you identify and fix sections with multiple faculty assignments.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if(count($duplicates) > 0)
        @foreach($duplicates as $item)
        <div class="card mb-3 border-danger">
            <div class="card-header bg-danger text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Section: <strong>{{ $item['section']->name }}</strong>
                        </h5>
                        <small>
                            {{ $item['section']->school_year }} - {{ $item['section']->term }} | 
                            Room: {{ $item['section']->room }}
                        </small>
                    </div>
                    <span class="badge bg-light text-danger" style="font-size: 1.2rem; padding: 8px 12px;">
                        {{ $item['count'] }} Faculty
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="mb-3"><i class="fas fa-users"></i> Faculty Members Assigned:</h6>
                
                <div class="table-responsive">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr class="table-light">
                                <th>Name</th>
                                <th>Employee ID</th>
                                <th>Email</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Assigned Since</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item['faculty'] as $faculty)
                            <tr>
                                <td>
                                    <strong>{{ $faculty->first_name . ' ' . $faculty->last_name }}</strong>
                                </td>
                                <td>{{ $faculty->employee_id ?? '--' }}</td>
                                <td>{{ $faculty->email }}</td>
                                <td>{{ $faculty->department ?? '--' }}</td>
                                <td>
                                    <span class="badge" style="background-color: {{ $faculty->status === 'active' ? '#27ae60' : '#e74c3c' }}; color: white;">
                                        {{ ucfirst($faculty->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $faculty->created_at->format('M d, Y') }}</small>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <hr>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="fas fa-magic"></i> Auto-Fix (Keep Oldest Faculty)</h6>
                        <form action="{{ route('faculty.fixDuplicate', $item['section']->id) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('This will keep the first-assigned faculty and remove others. Continue?');">
                                <i class="fas fa-check-circle me-2"></i> Auto-Fix Section
                            </button>
                        </form>
                        <small class="text-muted d-block mt-2">
                            Will keep: <strong>{{ $item['faculty']->first()->first_name . ' ' . $item['faculty']->first()->last_name }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6">
                        <h6 class="mb-3"><i class="fas fa-hand-pointer"></i> Manual Selection</h6>
                        <form action="{{ route('faculty.fixDuplicateManual', $item['section']->id) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="keep_faculty_id" class="form-select" required>
                                    <option value="">-- Select Faculty to Keep --</option>
                                    @foreach($item['faculty'] as $faculty)
                                    <option value="{{ $faculty->id }}">
                                        {{ $faculty->first_name . ' ' . $faculty->last_name }} ({{ $faculty->employee_id ?? 'N/A' }})
                                    </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-warning" onclick="return confirm('Confirm selection. This faculty will be kept, others removed.');">
                                    <i class="fas fa-sync-alt me-1"></i> Apply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

        <div class="card mt-4">
            <div class="card-body">
                <h5 class="mb-3">Quick Actions</h5>
                <div class="alert alert-info mb-3">
                    <i class="fas fa-lightbulb me-2"></i>
                    <strong>Recommendation:</strong> Review each duplicate carefully before fixing. Different faculty may have different reasons for being assigned to the same section.
                </div>
            </div>
        </div>
    @else
        <div class="alert alert-success" role="alert">
            <div class="text-center py-5">
                <i class="fas fa-check-circle" style="font-size: 3rem; color: #27ae60; margin-bottom: 15px; display: block;"></i>
                <h4 class="text-success">All Systems Nominal</h4>
                <p class="text-muted mb-0">
                    No duplicate faculty assignments detected. Each section has been correctly assigned with one faculty member.
                </p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h6><i class="fas fa-info-circle me-2"></i> What's Next?</h6>
                <ul class="mb-0">
                    <li>Review the <a href="/admin/sections">Sections page</a> to confirm all faculty assignments</li>
                    <li>Visit the <a href="/admin/users">User Management</a> page to manage faculty details</li>
                    <li>Return to this audit page periodically to maintain data integrity</li>
                </ul>
            </div>
        </div>
    @endif
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
    }

    .card-header {
        border-bottom: 2px solid var(--ojtms-accent);
        font-weight: 600;
    }

    .table-hover tbody tr:hover {
        background-color: var(--ojtms-light);
    }
</style>
@endsection
