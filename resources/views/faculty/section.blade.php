@extends('layouts.admin')

@section('title', 'Handled Section')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link active"><i class="fas fa-book-open"></i> Handled Section</a>
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-book-open"></i> Handled Section</h2>
    </div>

    <form method="GET" action="/faculty/section" class="row gy-2 gx-2 mb-3">
        <div class="col-md-4">
            <label class="form-label">School Year</label>
            <input type="text" name="school_year" value="{{ $filters['school_year'] ?? '' }}" class="form-control" placeholder="e.g. 2025-2026" />
        </div>
        <div class="col-md-4">
            <label class="form-label">Term</label>
            <input type="text" name="term" value="{{ $filters['term'] ?? '' }}" class="form-control" placeholder="e.g. 1st Semester" />
        </div>
        <div class="col-md-4 align-self-end">
            <button type="submit" class="btn btn-primary">Search / Filter</button>
            <a href="/faculty/section" class="btn btn-secondary">Clear</a>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            @if(isset($sections) && count($sections) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Section Name</th>
                                <th>School Year</th>
                                <th>Term</th>
                                <th>Day</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Students</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sections as $i => $section)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $section->name }}</td>
                                <td>{{ $section->school_year }}</td>
                                <td>{{ $section->term }}</td>
                                <td>{{ $section->day }}</td>
                                <td>{{ $section->start_time }} - {{ $section->end_time }}</td>
                                <td>{{ $section->room }}</td>
                                <td>
                                    <a href="/faculty/section/{{ $section->id }}/students" class="btn btn-sm btn-info">
                                        <i class="fas fa-users"></i> List of Students
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted">No section records found for your handled section(s).</p>
            @endif
        </div>
    </div>
</div>
@endsection