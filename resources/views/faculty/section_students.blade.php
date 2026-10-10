@extends('layouts.admin')

@section('title', 'Section Students')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/chatbot" class="nav-link"><i class="fas fa-robot"></i> AI Chatbot</a>
    <a href="/faculty/decision-support" class="nav-link"><i class="fas fa-brain"></i> Decision Support</a>
    <a href="/faculty/profile" class="nav-link"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-users"></i> Students in {{ $section->name ?? 'Section' }}</h2>

    <p class="text-muted">School Year: {{ $section->school_year ?? 'N/A' }} | Term: {{ $section->term ?? 'N/A' }}</p>

    <div class="mb-3">
        <a href="/faculty/section" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Sections</a>
    </div>

    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col-auto">
                    <label class="col-form-label"><i class="fas fa-filter me-1"></i>Company</label>
                </div>
                <div class="col-md-4 col-sm-6">
                    <select name="company" class="form-select" onchange="this.form.submit()">
                        <option value="">All students</option>
                        <option value="__none__" {{ $companyFilter === '__none__' ? 'selected' : '' }}>No company yet ({{ $noCompanyCount }})</option>
                        @foreach($companies as $company)
                        <option value="{{ $company }}" {{ $companyFilter === $company ? 'selected' : '' }}>{{ $company }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto text-muted small">
                    <span class="badge bg-success">{{ $withCompanyCount }}</span> with company
                    <span class="badge bg-secondary ms-2">{{ $noCompanyCount }}</span> no company yet
                </div>
                @if($companyFilter !== '')
                <div class="col-auto">
                    <a href="/faculty/section/{{ $section->id }}/students" class="btn btn-sm btn-outline-secondary">Clear filter</a>
                </div>
                @endif
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($students->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($students as $index => $student)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $student->first_name }} {{ $student->last_name }}</td>
                                <td>{{ $student->username }}</td>
                                <td>{{ $student->email }}</td>
                                <td>{{ $student->contact ?? 'N/A' }}</td>
                                <td>
                                    @if($student->company_name)
                                        {{ $student->company_name }}
                                        @if($student->company_address)<br><small class="text-muted">{{ $student->company_address }}</small>@endif
                                    @else
                                        <span class="badge bg-secondary">No company yet</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($student->status ?? 'active') }}</td>
                                <td>
                                    <a href="/faculty/section/{{ $section->id }}/students/{{ $student->id }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i> View Student
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">{{ $companyFilter !== '' ? 'No students match this company filter.' : 'No students currently assigned to this section.' }}</p>
            @endif
        </div>
    </div>
</div>
@endsection