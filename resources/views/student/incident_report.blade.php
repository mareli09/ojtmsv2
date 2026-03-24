@extends('layouts.student')

@section('title', 'Incident Report')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-exclamation-triangle"></i> Incident Report</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-plus-circle me-2"></i>Submit New Incident Report
                </div>
                <div class="card-body">
                    @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="/student/incident-report">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label"><strong>Incident Type</strong> <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required>
                                <option value="">-- Select type --</option>
                                <option value="Accident" {{ old('type') == 'Accident' ? 'selected' : '' }}>Accident / Injury</option>
                                <option value="Misconduct" {{ old('type') == 'Misconduct' ? 'selected' : '' }}>Misconduct / Harassment</option>
                                <option value="Property Damage" {{ old('type') == 'Property Damage' ? 'selected' : '' }}>Property Damage</option>
                                <option value="Health Issue" {{ old('type') == 'Health Issue' ? 'selected' : '' }}>Health Issue</option>
                                <option value="Security Concern" {{ old('type') == 'Security Concern' ? 'selected' : '' }}>Security Concern</option>
                                <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Date of Incident</strong> <span class="text-danger">*</span></label>
                            <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror"
                                value="{{ old('incident_date') }}" required>
                            @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Location</strong> <span class="text-danger">*</span></label>
                            <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                                value="{{ old('location') }}" placeholder="e.g., Company office, Worksite floor 2" required>
                            @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Description</strong> <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control @error('description') is-invalid @enderror"
                                rows="5" placeholder="Describe what happened in detail..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><strong>Action Taken (if any)</strong></label>
                            <textarea name="action_taken" class="form-control" rows="3"
                                placeholder="Describe any immediate actions taken...">{{ old('action_taken') }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane"></i> Submit Report
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-history me-2"></i>My Previous Reports
                </div>
                <div class="card-body p-0">
                    @if($reports->count() == 0)
                    <p class="text-muted p-3 mb-0">No reports submitted yet.</p>
                    @else
                    <ul class="list-group list-group-flush">
                        @foreach($reports as $report)
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $report->type }}</strong>
                                <small class="text-muted">{{ $report->incident_date->format('M d, Y') }}</small>
                            </div>
                            <small class="text-muted">{{ $report->location }}</small><br>
                            <small>{{ Str::limit($report->description, 80) }}</small>
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
