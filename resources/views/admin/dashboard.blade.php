@extends('layouts.admin')

@section('title', 'Dashboard')

@section('sidebar')
    <a href="/admin/dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
    <a href="/admin/sections" class="nav-link"><i class="fas fa-book"></i> Sections</a>
    <a href="/admin/users" class="nav-link"><i class="fas fa-users"></i> User Management</a>
    <a href="/admin/cms" class="nav-link"><i class="fas fa-cogs"></i> CMS Settings</a>
    <a href="/admin/announcements" class="nav-link"><i class="fas fa-megaphone"></i> Announcements</a>
    <a href="/admin/reports" class="nav-link"><i class="fas fa-file-pdf"></i> Reports</a>
@endsection

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-users">
                <h5>Total Users</h5>
                <h3>{{ $totalUsers ?? 125 }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-admins">
                <h5>Admins</h5>
                <h3>{{ $admins ?? 5 }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-faculty">
                <h5>Faculty</h5>
                <h3>{{ $faculty ?? 30 }}</h3>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card stat-card stat-students">
                <h5>Students</h5>
                <h3>{{ $students ?? 90 }}</h3>
            </div>
        </div>
    </div>

    <!-- Charts -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">User Registration Trend</div>
                <div class="card-body">
                    <canvas id="registrationChart" height="80"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">User Distribution</div>
                <div class="card-body">
                    <canvas id="distributionChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row">
        <div class="col-12">
            <a href="/admin/sections/create" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Create Section
            </a>
            <a href="/admin/users/create" class="btn btn-primary">
                <i class="fas fa-plus-circle"></i> Add New User
            </a>
            <a href="/admin/announcements/create" class="btn btn-primary">
                <i class="fas fa-bullhorn"></i> Create Announcement
            </a>
            <a href="/admin/reports" class="btn btn-secondary">
                <i class="fas fa-download"></i> Download Reports
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Registration trend chart
    const ctx1 = document.getElementById('registrationChart').getContext('2d');
    new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'New Users',
                data: [12, 19, 8, 25, 22, 30],
                borderColor: '#f4b61b',
                backgroundColor: 'rgba(244, 182, 27, 0.1)',
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#f4b61b',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#000156',
                        font: { size: 12, weight: 'bold' }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { color: '#000156' },
                    grid: { color: '#edeef0' }
                },
                x: {
                    ticks: { color: '#000156' },
                    grid: { color: '#edeef0' }
                }
            }
        }
    });

    // Distribution chart
    const ctx2 = document.getElementById('distributionChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Admin', 'Faculty', 'Student'],
            datasets: [{
                data: [5, 30, 90],
                backgroundColor: ['#667eea', '#f4b61b', '#27ae60'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#000156',
                        font: { size: 12, weight: 'bold' },
                        padding: 15
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
