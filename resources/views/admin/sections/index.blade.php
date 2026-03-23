@extends('layouts.admin')

@section('title', 'Manage Sections')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link active"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-megaphone"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-book"></i> Manage Sections</h2>
        <div>
            <a href="/admin/faculty-assignment-audit" class="btn btn-warning me-2" title="Check for duplicate faculty assignments">
                <i class="fas fa-stethoscope"></i> Faculty Audit
            </a>
            <a href="/admin/sections-archive" class="btn btn-secondary me-2">
                <i class="fas fa-archive"></i> Archived Sections
            </a>
            <a href="/admin/sections/create" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New Section
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
            <div class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" id="searchName" placeholder="Search section name...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterSchoolYear">
                        <option value="">All School Years</option>
                        <option value="2025-2026">2025-2026</option>
                        <option value="2026-2027">2026-2027</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterTerm">
                        <option value="">All Terms</option>
                        <option value="Term 1">Term 1</option>
                        <option value="Term 2">Term 2</option>
                        <option value="Term 3">Term 3</option>
                        <option value="Midyear">Midyear</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-secondary w-100" id="resetFilters">
                        <i class="fas fa-redo"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Sections</h5>
            <div>
                <small class="text-muted">Sort by: </small>
                <select class="form-select form-select-sm d-inline-block" style="width: auto;" id="sortBy">
                    <option value="name">Section Name</option>
                    <option value="school_year">School Year</option>
                    <option value="term">Term</option>
                    <option value="status">Status</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            @if($sections && count($sections) > 0)
            <div class="table-responsive">
                <table class="table table-hover" id="sectionsTable">
                    <thead>
                        <tr>
                            <th>Section Name</th>
                            <th>School Year</th>
                            <th>Term</th>
                            <th>Schedule</th>
                            <th>Room</th>
                            <th>Faculty</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sectionsTableBody">
                        @foreach($sections as $section)
                        <tr class="section-row" data-name="{{ $section->name ?? '' }}" data-school-year="{{ $section->school_year ?? '' }}" data-term="{{ $section->term ?? '' }}" data-status="{{ $section->status ?? '' }}">
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
                                @if($section->faculty)
                                    <span class="badge" style="background-color: var(--ojtms-primary); color: white;">
                                        {{ $section->faculty->first_name . ' ' . $section->faculty->last_name }}
                                    </span>
                                @else
                                    <em class="text-muted">--</em>
                                @endif
                            </td>
                            <td>
                                @php
                                    $statusColor = '#27ae60'; // active
                                    if ($section->status === 'inactive') {
                                        $statusColor = '#e74c3c'; // red
                                    } elseif ($section->status === 'completed') {
                                        $statusColor = '#95a5a6'; // gray
                                    }
                                @endphp
                                <span class="badge" style="background-color: {{ $statusColor }}; color: white;">
                                    {{ ucfirst($section->status ?? 'active') }}
                                </span>
                            </td>
                            <td>
                                <a href="/admin/sections/{{ $section->id ?? '1' }}/edit" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="/admin/sections/{{ $section->id ?? '1' }}/view" class="btn btn-sm btn-info" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="/admin/sections/{{ $section->id }}" method="POST" style="display: inline;" onsubmit="return confirmDelete(event)">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
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
                <p class="text-muted">No sections created yet.</p>
                <a href="/admin/sections/create" class="btn btn-primary mt-3">
                    <i class="fas fa-plus-circle"></i> Create First Section
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
        background-color: var(--ojtms-light);
    }

    .section-row.hidden {
        display: none;
    }

    .btn-warning {
        background-color: #f39c12;
        border: none;
        color: white;
    }

    .btn-warning:hover {
        background-color: #e67e22;
        color: white;
    }

    .btn-danger {
        background-color: #e74c3c;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    .badge {
        padding: 6px 12px;
        font-weight: 600;
    }

    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>

<script>
    function confirmDelete(event) {
        event.preventDefault();
        
        if (confirm('Are you sure you want to delete this section? It will be moved to the archive and can be restored later.')) {
            event.target.submit();
        }
        return false;
    }

    // Filter and Search Functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchName = document.getElementById('searchName');
        const filterSchoolYear = document.getElementById('filterSchoolYear');
        const filterTerm = document.getElementById('filterTerm');
        const filterStatus = document.getElementById('filterStatus');
        const sortBy = document.getElementById('sortBy');
        const resetFilters = document.getElementById('resetFilters');
        const tableBody = document.getElementById('sectionsTableBody');
        const rows = document.querySelectorAll('.section-row');

        function filterTable() {
            const searchValue = searchName.value.toLowerCase();
            const schoolYearValue = filterSchoolYear.value;
            const termValue = filterTerm.value;
            const statusValue = filterStatus.value;

            rows.forEach(row => {
                const name = row.getAttribute('data-name').toLowerCase();
                const schoolYear = row.getAttribute('data-school-year');
                const term = row.getAttribute('data-term');
                const status = row.getAttribute('data-status');

                let show = true;

                if (searchValue && !name.includes(searchValue)) show = false;
                if (schoolYearValue && schoolYear !== schoolYearValue) show = false;
                if (termValue && term !== termValue) show = false;
                if (statusValue && status !== statusValue) show = false;

                row.classList.toggle('hidden', !show);
            });
        }

        function sortTable() {
            const sortValue = sortBy.value;
            const rowsArray = Array.from(rows);

            rowsArray.sort((a, b) => {
                let aValue, bValue;

                if (sortValue === 'name') {
                    aValue = a.getAttribute('data-name');
                    bValue = b.getAttribute('data-name');
                } else if (sortValue === 'school_year') {
                    aValue = a.getAttribute('data-school-year');
                    bValue = b.getAttribute('data-school-year');
                } else if (sortValue === 'term') {
                    aValue = a.getAttribute('data-term');
                    bValue = b.getAttribute('data-term');
                } else if (sortValue === 'status') {
                    aValue = a.getAttribute('data-status');
                    bValue = b.getAttribute('data-status');
                }

                return aValue.localeCompare(bValue);
            });

            tableBody.innerHTML = '';
            rowsArray.forEach(row => tableBody.appendChild(row));
        }

        // Event listeners
        searchName.addEventListener('keyup', filterTable);
        filterSchoolYear.addEventListener('change', filterTable);
        filterTerm.addEventListener('change', filterTable);
        filterStatus.addEventListener('change', filterTable);
        sortBy.addEventListener('change', sortTable);

        resetFilters.addEventListener('click', function() {
            searchName.value = '';
            filterSchoolYear.value = '';
            filterTerm.value = '';
            filterStatus.value = '';
            sortBy.value = 'name';
            rows.forEach(row => row.classList.remove('hidden'));
        });
    });
</script>
@endsection
