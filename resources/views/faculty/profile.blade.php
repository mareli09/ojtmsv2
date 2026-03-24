@extends('layouts.admin')

@section('title', 'Faculty Profile Settings')

@section('sidebar')
    <a href="/faculty/dashboard" class="nav-link"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/faculty/section" class="nav-link"><i class="fas fa-book-open"></i> Handled Section</a>
    <a href="/faculty/incident-reports" class="nav-link"><i class="fas fa-exclamation-triangle"></i> Incident Reports</a>
    <a href="/faculty/reports" class="nav-link"><i class="fas fa-file-alt"></i> Reports</a>
    <a href="/faculty/faqs" class="nav-link"><i class="fas fa-question-circle"></i> FAQ Management</a>
    <a href="/faculty/announcements" class="nav-link"><i class="fas fa-bullhorn"></i> Announcements</a>
    <a href="/faculty/profile" class="nav-link active"><i class="fas fa-user-cog"></i> Profile Settings</a>
    <a href="/logout" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-user-cog"></i> Profile Settings</h2>
    <div class="card mt-3">
        <div class="card-body">
            <p>This is the faculty profile management placeholder. You can implement profile editing, password change and preferences here.</p>
            <hr>
            <h4>Faculty Info</h4>
            <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Department:</strong> {{ $user->department ?? 'N/A' }}</p>
            <p><strong>Contact:</strong> {{ $user->contact ?? 'N/A' }}</p>
            <p><strong>Assigned Section:</strong> {{ $section ? $section->name : 'Not assigned' }}</p>
        </div>
    </div>
</div>
@endsection