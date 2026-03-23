@extends('layouts.admin')

@section('title', 'Edit Announcement')

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
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-edit"></i> Edit Announcement</h2>
    </div>

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="/admin/announcements/{{ $announcement->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8">
                <!-- Title -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-heading"></i> Announcement Title</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                id="title" name="title" 
                                value="{{ old('title', $announcement->title) }}" 
                                placeholder="Enter announcement title"
                                required maxlength="255">
                            <small class="form-text text-muted">Keep it concise and descriptive</small>
                            @error('title')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-file-alt"></i> Content</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="content" class="form-label">Announcement Content <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                id="content" name="content" 
                                rows="8"
                                placeholder="Write your announcement content here..."
                                required>{{ old('content', $announcement->content) }}</textarea>
                            <small class="form-text text-muted">Provide detailed information about your announcement</small>
                            @error('content')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-check-square"></i> Status</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Visibility Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                id="status" name="status" required>
                                <option value="active" {{ old('status', $announcement->status) == 'active' ? 'selected' : '' }}>
                                    <i class="fas fa-eye"></i> Active (Visible on landing page)
                                </option>
                                <option value="inactive" {{ old('status', $announcement->status) == 'inactive' ? 'selected' : '' }}>
                                    <i class="fas fa-eye-slash"></i> Inactive (Hidden from landing page)
                                </option>
                            </select>
                            <small class="form-text text-muted">Choose whether this announcement should be visible on the landing page</small>
                            @error('status')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Changes
                    </button>
                    <a href="/admin/announcements" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Announcements
                    </a>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-eye"></i> Live Preview</h5>
                    </div>
                    <div class="card-body">
                        <div class="preview-section">
                            <h6 class="text-muted mb-2">How it will appear on the landing page:</h6>
                            
                            <div class="announcement-card bg-light p-3 rounded">
                                <h5 class="fw-bold mb-2" id="preview-title">{{ $announcement->title }}</h5>
                                <p class="announcement-date small mb-3">
                                    <i class="far fa-calendar me-2"></i><span id="preview-date">{{ $announcement->created_at->format('M d, Y') }}</span>
                                </p>
                                <p class="text-primary-custom mb-0" id="preview-content" style="font-size: 0.9rem;">
                                    {{ Str::limit($announcement->content, 100) }}
                                </p>
                            </div>
                        </div>

                        <hr>

                        <div class="status-info mt-3">
                            <h6 class="text-muted mb-2">Current Status:</h6>
                            <div class="badge @if($announcement->status === 'active') bg-success @else bg-warning @endif">
                                <i class="fas @if($announcement->status === 'active') fa-check-circle @else fa-pause-circle @endif me-1"></i>
                                {{ ucfirst($announcement->status) }}
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <small>Changes will be saved and reflected on the landing page immediately.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 1, 86, 0.08);
        border-radius: 8px;
    }

    .card-header {
        background-color: var(--ojtms-light);
        border-bottom: 2px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        font-weight: 600;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: var(--ojtms-accent);
        box-shadow: 0 0 0 0.2rem rgba(244, 182, 27, 0.15);
    }

    textarea.form-control {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        resize: vertical;
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

    .form-select {
        border: 2px solid var(--ojtms-light);
        border-radius: 5px;
    }

    .announcement-card {
        border-left: 4px solid var(--ojtms-accent);
        box-shadow: 0 2px 8px rgba(0,1,86,0.08);
    }

    .announcement-date {
        color: var(--ojtms-accent);
        font-weight: 600;
    }

    .text-primary-custom {
        color: var(--ojtms-primary);
    }
</style>

<script>
    // Live preview
    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('content');
    const previewTitle = document.getElementById('preview-title');
    const previewContent = document.getElementById('preview-content');

    titleInput.addEventListener('input', function() {
        previewTitle.textContent = this.value || 'Announcement Title';
    });

    contentInput.addEventListener('input', function() {
        const preview = this.value ? this.value.substring(0, 100) : 'Content preview will appear here...';
        previewContent.textContent = preview + (this.value.length > 100 ? '...' : '');
    });
</script>
@endsection
