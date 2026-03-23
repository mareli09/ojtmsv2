@extends('layouts.admin')

@section('title', 'Reports & Exports')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link active"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-file-csv"></i> Reports & Data Exports</h2>
    </div>

    <small class="text-muted mb-4 d-block">Download all database tables in CSV format for analysis and backup purposes</small>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row g-4">
        
        <!-- Users Export Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-users"></i> Users</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Total Records:</strong> {{ $usersCount }}
                        </p>
                        <small class="text-muted d-block mb-3">
                            Includes all admins, faculty members, and students with their contact information and department details.
                        </small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="/admin/reports/export/users" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-2"></i>CSV
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="showPreview('users')">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections Export Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-book"></i> Sections</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Total Records:</strong> {{ $sectionsCount }}
                        </p>
                        <small class="text-muted d-block mb-3">
                            All OJT sections including schedule, room, capacity, and faculty assignment information.
                        </small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="/admin/reports/export/sections" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-2"></i>CSV
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="showPreview('sections')">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Announcements Export Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-bullhorn"></i> Announcements</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Total Records:</strong> {{ $announcementsCount }}
                        </p>
                        <small class="text-muted d-block mb-3">
                            All system announcements including title, content, status, and publication dates.
                        </small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="/admin/reports/export/announcements" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-2"></i>CSV
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="showPreview('announcements')">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- CMS Settings Export Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> CMS Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Total Records:</strong> {{ $cmsCount }}
                        </p>
                        <small class="text-muted d-block mb-3">
                            All CMS configuration settings including header, footer, contact info, and social media links.
                        </small>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="/admin/reports/export/cms" class="btn btn-sm btn-primary">
                            <i class="fas fa-download me-2"></i>CSV
                        </a>
                        <button class="btn btn-sm btn-outline-secondary" onclick="showPreview('cms')">
                            <i class="fas fa-eye me-2"></i>Preview
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Complete Database Backup Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100 border-success">
                <div class="card-header bg-success">
                    <h5 class="mb-0 text-white"><i class="fas fa-database"></i> Complete Database</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="text-muted mb-2">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>All Tables:</strong> 4 tables included
                        </p>
                        <small class="text-muted d-block mb-3">
                            Download all tables at once. Creates a ZIP file with individual CSV files for each table.
                        </small>
                    </div>
                    <a href="/admin/reports/export/all" class="btn btn-sm btn-success w-100">
                        <i class="fas fa-download me-2"></i>Download All (ZIP)
                    </a>
                </div>
            </div>
        </div>

        <!-- Export Statistics Card -->
        <div class="col-md-6 col-lg-4">
            <div class="export-card card h-100 border-info">
                <div class="card-header bg-info">
                    <h5 class="mb-0 text-white"><i class="fas fa-chart-bar"></i> Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="stat-item mb-3">
                        <small class="text-muted">Active Users:</small>
                        <p class="mb-0"><strong>{{ $activeUsersCount }}</strong></p>
                    </div>
                    <div class="stat-item mb-3">
                        <small class="text-muted">Active Sections:</small>
                        <p class="mb-0"><strong>{{ $activeSectionsCount }}</strong></p>
                    </div>
                    <div class="stat-item mb-3">
                        <small class="text-muted">Active Announcements:</small>
                        <p class="mb-0"><strong>{{ $activeAnnouncementsCount }}</strong></p>
                    </div>
                    <div class="stat-item">
                        <small class="text-muted">Last Export Generated:</small>
                        <p class="mb-0"><small>On demand</small></p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Help Section -->
    <div class="card mt-5">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-question-circle"></i> How to Use</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-primary-custom">Exporting Data</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i> Click the <strong>CSV</strong> button to download individual tables</li>
                        <li><i class="fas fa-check text-success me-2"></i> Files are downloaded in CSV format for Excel/Google Sheets compatibility</li>
                        <li><i class="fas fa-check text-success me-2"></i> Use <strong>Preview</strong> to see a sample of the data before downloading</li>
                        <li><i class="fas fa-check text-success me-2"></i> Download <strong>All</strong> for a complete database backup</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6 class="text-primary-custom">File Format</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-file-csv text-danger me-2"></i> <strong>CSV</strong> - Comma Separated Values (Excel compatible)</li>
                        <li><i class="fas fa-info-circle text-info me-2"></i> UTF-8 encoded for international character support</li>
                        <li><i class="fas fa-shield-alt text-warning me-2"></i> No sensitive data like passwords is included</li>
                        <li><i class="fas fa-calendar text-muted me-2"></i> Timestamp: {{ now()->format('M d, Y H:i:s') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-eye me-2"></i>Data Preview - <span id="previewTitle"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="previewContent" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
        border-radius: 8px;
        transition: all 0.3s;
    }

    .card:hover {
        box-shadow: 0 6px 20px rgba(0, 1, 86, 0.12);
        transform: translateY(-2px);
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }

    .export-card .btn-group {
        display: flex;
        gap: 5px;
    }

    .export-card .btn-sm {
        flex: 1;
    }

    .stat-item {
        padding: 8px 0;
        border-bottom: 1px solid var(--ojtms-light);
    }

    .stat-item:last-child {
        border-bottom: none;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--ojtms-primary) 0%, var(--ojtms-dark) 100%);
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 1, 86, 0.2);
    }

    .bg-success {
        background-color: #28a745 !important;
    }

    .bg-info {
        background-color: #17a2b8 !important;
    }

    .border-success {
        border: 1px solid #28a745 !important;
    }

    .border-info {
        border: 1px solid #17a2b8 !important;
    }

    .text-primary-custom {
        color: var(--ojtms-primary);
    }

    table.preview-table {
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    table.preview-table th {
        background-color: var(--ojtms-light);
        color: var(--ojtms-primary);
        font-weight: 600;
        white-space: nowrap;
        padding: 8px;
    }

    table.preview-table td {
        padding: 8px;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>

<script>
    function showPreview(table) {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewTitle = document.getElementById('previewTitle');
        const previewContent = document.getElementById('previewContent');

        previewTitle.textContent = table.charAt(0).toUpperCase() + table.slice(1);
        previewContent.innerHTML = '<p class="text-center text-muted"><i class="fas fa-spinner fa-spin"></i> Loading...</p>';

        fetch(`/admin/reports/preview/${table}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.html) {
                    previewContent.innerHTML = data.html;
                } else {
                    previewContent.innerHTML = '<div class="alert alert-danger">Unable to load preview</div>';
                }
            })
            .catch(error => {
                previewContent.innerHTML = '<div class="alert alert-danger">Error loading preview</div>';
                console.error('Error:', error);
            });

        modal.show();
    }
</script>
@endsection
