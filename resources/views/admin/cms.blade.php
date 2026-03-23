@extends('layouts.admin')

@section('title', 'CMS Settings')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link active"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex align-items-center mb-4">
        <h2 class="mb-0"><i class="fas fa-cogs"></i> CMS Settings</h2>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <form action="{{ route('cms.update') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8">
                <!-- Header Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-heading"></i> Header & Tagline</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="header" class="form-label">Main Header <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('header') is-invalid @enderror" 
                                id="header" name="header" 
                                value="{{ $cms->get('header')->value ?? 'AI-Assisted OJT Monitoring System' }}" 
                                required>
                            <small class="form-text text-muted">This appears as the main title on the landing page</small>
                            @error('header')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="subheader" class="form-label">Subheader <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('subheader') is-invalid @enderror" 
                                id="subheader" name="subheader" 
                                value="{{ $cms->get('subheader')->value ?? 'Streamline On-the-Job Training Management with Intelligent Analytics' }}" 
                                required>
                            <small class="form-text text-muted">Tagline shown below the main header</small>
                            @error('subheader')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- About Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> About OJTMS</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="about" class="form-label">About Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('about') is-invalid @enderror" 
                                id="about" name="about" rows="4" required>{{ $cms->get('about')->value ?? 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.' }}</textarea>
                            <small class="form-text text-muted">Describe your OJT system</small>
                            @error('about')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Mission & Vision Section -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-rocket"></i> Mission</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="mission" class="form-label">Mission Statement <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('mission') is-invalid @enderror" 
                                        id="mission" name="mission" rows="4" required>{{ $cms->get('mission')->value ?? 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.' }}</textarea>
                                    @error('mission')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0"><i class="fas fa-eye"></i> Vision</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="vision" class="form-label">Vision Statement <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('vision') is-invalid @enderror" 
                                        id="vision" name="vision" rows="4" required>{{ $cms->get('vision')->value ?? 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.' }}</textarea>
                                    @error('vision')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-address-book"></i> Contact Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('contact_email') is-invalid @enderror" 
                                        id="contact_email" name="contact_email" 
                                        value="{{ $cms->get('contact_email')->value ?? 'ojtms@example.edu.ph' }}" 
                                        required>
                                    @error('contact_email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact_phone" class="form-label">Phone Number <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                        id="contact_phone" name="contact_phone" 
                                        value="{{ $cms->get('contact_phone')->value ?? '+63 900 000 0000' }}" 
                                        required>
                                    @error('contact_phone')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="contact_address" class="form-label">Office Address <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('contact_address') is-invalid @enderror" 
                                id="contact_address" name="contact_address" 
                                value="{{ $cms->get('contact_address')->value ?? 'Sample City, Philippines' }}" 
                                required>
                            @error('contact_address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Social Media Links Section -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-share-alt"></i> Social Media Links</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="facebook_url" class="form-label"><i class="fab fa-facebook"></i> Facebook URL</label>
                                    <input type="url" class="form-control @error('facebook_url') is-invalid @enderror" 
                                        id="facebook_url" name="facebook_url" 
                                        value="{{ $cms->get('facebook_url')->value ?? 'https://facebook.com' }}" 
                                        placeholder="https://facebook.com/yourpage">
                                    @error('facebook_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="instagram_url" class="form-label"><i class="fab fa-instagram"></i> Instagram URL</label>
                                    <input type="url" class="form-control @error('instagram_url') is-invalid @enderror" 
                                        id="instagram_url" name="instagram_url" 
                                        value="{{ $cms->get('instagram_url')->value ?? 'https://instagram.com' }}" 
                                        placeholder="https://instagram.com/yourprofile">
                                    @error('instagram_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="linkedin_url" class="form-label"><i class="fab fa-linkedin"></i> LinkedIn URL</label>
                                    <input type="url" class="form-control @error('linkedin_url') is-invalid @enderror" 
                                        id="linkedin_url" name="linkedin_url" 
                                        value="{{ $cms->get('linkedin_url')->value ?? 'https://linkedin.com' }}" 
                                        placeholder="https://linkedin.com/company/yourcompany">
                                    @error('linkedin_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="twitter_url" class="form-label"><i class="fab fa-twitter"></i> Twitter URL</label>
                                    <input type="url" class="form-control @error('twitter_url') is-invalid @enderror" 
                                        id="twitter_url" name="twitter_url" 
                                        value="{{ $cms->get('twitter_url')->value ?? 'https://twitter.com' }}" 
                                        placeholder="https://twitter.com/yourhandle">
                                    @error('twitter_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="youtube_url" class="form-label"><i class="fab fa-youtube"></i> YouTube URL</label>
                                    <input type="url" class="form-control @error('youtube_url') is-invalid @enderror" 
                                        id="youtube_url" name="youtube_url" 
                                        value="{{ $cms->get('youtube_url')->value ?? 'https://youtube.com' }}" 
                                        placeholder="https://youtube.com/yourchannel">
                                    @error('youtube_url')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mb-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                    <a href="/admin/dashboard" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
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
                            <h6 class="text-muted mb-1">Header:</h6>
                            <div class="preview-content" id="preview-header">
                                AI-Assisted OJT Monitoring System
                            </div>
                        </div>

                        <hr>

                        <div class="preview-section">
                            <h6 class="text-muted mb-1">Subheader:</h6>
                            <div class="preview-content" id="preview-subheader" style="font-size: 0.9rem;">
                                Streamline On-the-Job Training Management with Intelligent Analytics
                            </div>
                        </div>

                        <hr>

                        <div class="preview-section">
                            <h6 class="text-muted mb-1">About:</h6>
                            <div class="preview-content" id="preview-about" style="font-size: 0.85rem;">
                                The OJT Monitoring System (OJTMS) is committed...
                            </div>
                        </div>

                        <hr>

                        <div class="preview-section">
                            <h6 class="text-muted mb-1"><i class="fas fa-rocket"></i> Mission:</h6>
                            <div class="preview-content" id="preview-mission" style="font-size: 0.8rem;">
                                To empower students...
                            </div>
                        </div>

                        <hr>

                        <div class="preview-section">
                            <h6 class="text-muted mb-1"><i class="fas fa-eye"></i> Vision:</h6>
                            <div class="preview-content" id="preview-vision" style="font-size: 0.8rem;">
                                A comprehensive platform...
                            </div>
                        </div>

                        <hr>

                        <div class="preview-section">
                            <h6 class="text-muted mb-1"><i class="fas fa-envelope"></i> Contact:</h6>
                            <div class="preview-content" id="preview-contact" style="font-size: 0.8rem;">
                                Email, Phone, Address
                            </div>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i> Changes will be automatically reflected on the landing page after saving.
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

    .form-control:focus {
        border-color: var(--ojtms-accent);
        box-shadow: 0 0 0 0.2rem rgba(244, 182, 27, 0.15);
    }

    textarea.form-control {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        resize: vertical;
    }

    .preview-section {
        margin-bottom: 15px;
    }

    .preview-content {
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 5px;
        border-left: 3px solid var(--ojtms-accent);
        color: var(--ojtms-primary);
        word-wrap: break-word;
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
</style>

<script>
    // Live preview functionality
    const headerInput = document.getElementById('header');
    const subheaderInput = document.getElementById('subheader');
    const aboutInput = document.getElementById('about');

    const previewHeader = document.getElementById('preview-header');
    const previewSubheader = document.getElementById('preview-subheader');
    const previewAbout = document.getElementById('preview-about');

    headerInput?.addEventListener('input', function() {
        previewHeader.textContent = this.value || 'AI-Assisted OJT Monitoring System';
    });

    subheaderInput?.addEventListener('input', function() {
        previewSubheader.textContent = this.value || 'Streamline On-the-Job Training Management with Intelligent Analytics';
    });

    aboutInput?.addEventListener('input', function() {
        const text = this.value || 'The OJT Monitoring System (OJTMS) is committed...';
        previewAbout.textContent = text.length > 100 ? text.substring(0, 100) + '...' : text;
    });
</script>
@endsection
