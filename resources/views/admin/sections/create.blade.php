@extends('layouts.admin')

@section('title', isset($section) ? 'Edit Section' : 'Create New Section')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link active"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
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
                    <form method="POST" action="{{ isset($section) ? '/admin/sections/' . $section->id : '/admin/sections' }}">
                        @csrf

                        <!-- Section Name and School Year Row -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="section_name" class="form-label">Section Name</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="section_name" 
                                    name="section_name" 
                                    placeholder="e.g., A1, B2, C3"
                                    value="{{ isset($section) ? $section->name : '' }}"
                                    required
                                >
                                <small class="text-muted">Simple section identifier (A1, B2, etc.)</small>
                            </div>
                            <div class="col-md-6">
                                <label for="school_year" class="form-label">School Year</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="school_year" 
                                    name="school_year" 
                                    placeholder="e.g., 2025-2026"
                                    value="{{ isset($section) ? $section->school_year : '' }}"
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
                                    <option value="Term 1" {{ (isset($section) && $section->term == 'Term 1') ? 'selected' : '' }}>Term 1</option>
                                    <option value="Term 2" {{ (isset($section) && $section->term == 'Term 2') ? 'selected' : '' }}>Term 2</option>
                                    <option value="Term 3" {{ (isset($section) && $section->term == 'Term 3') ? 'selected' : '' }}>Term 3</option>
                                    <option value="Midyear" {{ (isset($section) && $section->term == 'Midyear') ? 'selected' : '' }}>Midyear</option>
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
                                    value="{{ isset($section) ? $section->room : '' }}"
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
                                    <option value="Monday" {{ (isset($section) && $section->day == 'Monday') ? 'selected' : '' }}>Monday</option>
                                    <option value="Tuesday" {{ (isset($section) && $section->day == 'Tuesday') ? 'selected' : '' }}>Tuesday</option>
                                    <option value="Wednesday" {{ (isset($section) && $section->day == 'Wednesday') ? 'selected' : '' }}>Wednesday</option>
                                    <option value="Thursday" {{ (isset($section) && $section->day == 'Thursday') ? 'selected' : '' }}>Thursday</option>
                                    <option value="Friday" {{ (isset($section) && $section->day == 'Friday') ? 'selected' : '' }}>Friday</option>
                                    <option value="Saturday" {{ (isset($section) && $section->day == 'Saturday') ? 'selected' : '' }}>Saturday</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input 
                                    type="time" 
                                    class="form-control" 
                                    id="start_time" 
                                    name="start_time"
                                    value="{{ isset($section) ? $section->start_time : '' }}"
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
                                    value="{{ isset($section) ? $section->end_time : '' }}"
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
                                    value="{{ isset($section) ? $section->days_count : '1' }}"
                                    required
                                >
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
                            >{{ isset($section) ? $section->description : '' }}</textarea>
                        </div>

                        <!-- Faculty Assignment Alert (if already assigned) -->
                        @if(isset($section) && $section->faculty)
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Faculty Already Assigned:</strong>
                            <br>
                            <span class="ms-4">
                                <strong>{{ $section->faculty->first_name . ' ' . $section->faculty->last_name }}</strong> ({{ $section->faculty->employee_id ?? 'N/A' }}) 
                                is currently assigned to this section. 
                                <br>
                                <em>Each section can have only ONE faculty member. If you need to change the faculty member, please contact the admin.</em>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <!-- Faculty Assignment -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="faculty_id" class="form-label">Assign Faculty (Optional)</label>
                                <select class="form-select @error('faculty_id') is-invalid @enderror" id="faculty_id" name="faculty_id">
                                    <option value="">-- Not Assigned --</option>
                                    @forelse($availableFaculty as $faculty)
                                        <option value="{{ $faculty->id }}" {{ (isset($section) && $section->faculty_id == $faculty->id) ? 'selected' : '' }}>
                                            {{ $faculty->first_name . ' ' . $faculty->last_name }} ({{ $faculty->employee_id ?? 'N/A' }})
                                        </option>
                                    @empty
                                        <option disabled>No available faculty</option>
                                    @endforelse
                                </select>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-exclamation-triangle text-warning"></i>
                                    <strong>Only ONE faculty per section allowed!</strong> If a faculty is already assigned to a section, they will not appear in this list.
                                </small>
                                @error('faculty_id')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-times-circle me-1"></i> {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="capacity" class="form-label">Student Capacity (Optional)</label>
                                <input 
                                    type="number" 
                                    class="form-control" 
                                    id="capacity" 
                                    name="capacity"
                                    min="1" 
                                    max="100"
                                    value="{{ isset($section) && $section->capacity ? $section->capacity : '' }}"
                                    placeholder="e.g., 30"
                                >
                                <small class="text-muted">Will be populated during registration phase</small>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active" {{ (isset($section) && $section->status == 'active') ? 'selected' : 'selected' }}>Active</option>
                                <option value="inactive" {{ (isset($section) && $section->status == 'inactive') ? 'selected' : '' }}>Inactive</option>
                                <option value="completed" {{ (isset($section) && $section->status == 'completed') ? 'selected' : '' }}>Completed</option>
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
                            <p>A section is a class grouping with a defined schedule and location. Faculty members and students will be assigned to sections during the registration phase.</p>
                            <p class="mb-0"><strong>Note:</strong> Keep faculty and student capacity fields empty at this stage. They will be configured during registration.</p>
                        </small>
                    </div>

                    <div class="alert alert-warning">
                        <h6 class="alert-heading"><i class="fas fa-lightbulb me-2"></i>Tips</h6>
                        <small>
                            <ul class="mb-0">
                                <li>Use simple section names (A1, B2, etc.)</li>
                                <li>Ensure no schedule conflicts</li>
                                <li>Faculty and student assignment will be handled during registration</li>
                                <li>Description is optional</li>
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
