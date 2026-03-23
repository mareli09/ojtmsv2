@extends('layouts.admin')

@section('title', isset($section) ? 'Edit Section' : 'Create New Section')

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
    <div class="d-flex align-items-center mb-4">
        <a href="/admin/sections" class="btn btn-secondary me-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>{{ isset($section) ? 'Edit Section' : 'Create New Section' }}</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Section Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ isset($section) ? '/admin/sections/' . $section['id'] : '/admin/sections' }}">
                        @csrf

                        <!-- Section Code and School Year Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="section_code" class="form-label">Section Code</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="section_code" 
                                    name="section_code" 
                                    placeholder="e.g., OJT-2025-001"
                                    value="{{ $section['code'] ?? '' }}"
                                    required
                                >
                                <small class="text-muted">Unique identifier for this section</small>
                            </div>
                            <div class="col-md-6">
                                <label for="school_year" class="form-label">School Year</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="school_year" 
                                    name="school_year" 
                                    placeholder="e.g., 2025-2026"
                                    value="{{ $section['school_year'] ?? '' }}"
                                    required
                                >
                                <small class="text-muted">Format: YYYY-YYYY</small>
                            </div>
                        </div>

                        <!-- Term Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="term" class="form-label">Term</label>
                                <select class="form-select" id="term" name="term" required>
                                    <option value="">-- Select Term --</option>
                                    <option value="Term 1" {{ (isset($section) && $section['term'] == 'Term 1') ? 'selected' : '' }}>Term 1</option>
                                    <option value="Term 2" {{ (isset($section) && $section['term'] == 'Term 2') ? 'selected' : '' }}>Term 2</option>
                                    <option value="Term 3" {{ (isset($section) && $section['term'] == 'Term 3') ? 'selected' : '' }}>Term 3</option>
                                    <option value="Midyear" {{ (isset($section) && $section['term'] == 'Midyear') ? 'selected' : '' }}>Midyear</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="room" class="form-label">Room / Location</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="room" 
                                    name="room" 
                                    placeholder="e.g., Room 101, Lab A"
                                    value="{{ $section['room'] ?? '' }}"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Schedule Row -->
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Schedule</label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="day" class="form-label">Day</label>
                                <select class="form-select" id="day" name="day" required>
                                    <option value="">-- Select Day --</option>
                                    <option value="Monday" {{ (isset($section) && $section['day'] == 'Monday') ? 'selected' : '' }}>Monday</option>
                                    <option value="Tuesday" {{ (isset($section) && $section['day'] == 'Tuesday') ? 'selected' : '' }}>Tuesday</option>
                                    <option value="Wednesday" {{ (isset($section) && $section['day'] == 'Wednesday') ? 'selected' : '' }}>Wednesday</option>
                                    <option value="Thursday" {{ (isset($section) && $section['day'] == 'Thursday') ? 'selected' : '' }}>Thursday</option>
                                    <option value="Friday" {{ (isset($section) && $section['day'] == 'Friday') ? 'selected' : '' }}>Friday</option>
                                    <option value="Saturday" {{ (isset($section) && $section['day'] == 'Saturday') ? 'selected' : '' }}>Saturday</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input 
                                    type="time" 
                                    class="form-control" 
                                    id="start_time" 
                                    name="start_time"
                                    value="{{ $section['start_time'] ?? '' }}"
                                    required
                                >
                            </div>
                            <div class="col-md-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input 
                                    type="time" 
                                    class="form-control" 
                                    id="end_time" 
                                    name="end_time"
                                    value="{{ $section['end_time'] ?? '' }}"
                                    required
                                >
                            </div>
                            <div class="col-md-3">
                                <label for="days_count" class="form-label">Days per Week</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="days_count" 
                                    name="days_count"
                                    min="1" 
                                    max="7"
                                    value="{{ $section['days_count'] ?? '1' }}"
                                    required
                                >
                            </div>
                        </div>

                        <!-- Faculty Assignment -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="faculty_id" class="form-label">Assign Faculty</label>
                                <select class="form-select" id="faculty_id" name="faculty_id" required>
                                    <option value="">-- Select Faculty --</option>
                                    <option value="1" {{ (isset($section) && $section['faculty_id'] == '1') ? 'selected' : '' }}>Dr. Juan Dela Cruz</option>
                                    <option value="2" {{ (isset($section) && $section['faculty_id'] == '2') ? 'selected' : '' }}>Prof. Maria Santos</option>
                                    <option value="3" {{ (isset($section) && $section['faculty_id'] == '3') ? 'selected' : '' }}>Engr. Carlos Lopez</option>
                                </select>
                                <small class="text-muted">Select the faculty advisor for this section</small>
                            </div>
                            <div class="col-md-6">
                                <label for="capacity" class="form-label">Student Capacity</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="capacity" 
                                    name="capacity"
                                    min="1" 
                                    max="100"
                                    value="{{ $section['capacity'] ?? '30' }}"
                                    required
                                >
                                <small class="text-muted">Maximum number of students allowed</small>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea 
                                class="form-control" 
                                id="description" 
                                name="description" 
                                rows="4"
                                placeholder="Enter section description, requirements, or special notes"
                            >{{ isset($section) ? $section['description'] : '' }}</textarea>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="Active" {{ (isset($section) && $section['status'] == 'Active') ? 'selected' : 'selected' }}>Active</option>
                                <option value="Inactive" {{ (isset($section) && $section['status'] == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                <option value="Closed" {{ (isset($section) && $section['status'] == 'Closed') ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 justify-content-between mt-4">
                            <a href="/admin/sections" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ isset($section) ? 'Update Section' : 'Create Section' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info mb-3">
                        <h6 class="alert-heading"><i class="fas fa-info-circle me-2"></i>Section Details</h6>
                        <small>
                            <p><strong>What is a Section?</strong></p>
                            <p>A section is a class grouping of students assigned to a faculty mentor for On-the-Job Training (OJT). It defines the schedule, location, and connection between faculty and students.</p>
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <small>
                            <ul class="mb-0">
                                <li>Use descriptive section codes</li>
                                <li>Ensure no schedule conflicts</li>
                                <li>Set appropriate capacity</li>
                                <li>Assign experienced faculty</li>
                            </ul>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .form-label {
        color: var(--ojtms-primary);
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        border: 2px solid var(--ojtms-light);
        border-radius: 5px;
    }

    .form-control:focus, .form-select:focus {
        border-color: var(--ojtms-accent);
        box-shadow: 0 0 0 0.2rem rgba(244, 182, 27, 0.15);
    }

    .alert-info {
        background-color: rgba(102, 126, 234, 0.1);
        border-color: #667eea;
        color: var(--ojtms-primary);
    }

    .alert-warning {
        background-color: rgba(244, 182, 27, 0.1);
        border-color: var(--ojtms-accent);
        color: var(--ojtms-primary);
    }
</style>
@endsection
