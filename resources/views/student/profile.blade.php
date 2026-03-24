@extends('layouts.student')

@section('title', 'Profile Settings')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-user-cog"></i> Profile Settings</h2>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="row">
        <!-- Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card text-center">
                <div class="card-body py-4">
                    <div style="width:80px;height:80px;border-radius:50%;background:var(--ojtms-primary);display:flex;align-items:center;justify-content:center;margin:0 auto 15px;">
                        <i class="fas fa-user-graduate fa-2x" style="color:var(--ojtms-accent);"></i>
                    </div>
                    <h5 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <span class="badge bg-success mb-2">Student</span>
                    <p class="text-muted small mb-1"><i class="fas fa-id-card me-1"></i> {{ $user->student_id ?? 'N/A' }}</p>
                    <p class="text-muted small mb-1"><i class="fas fa-envelope me-1"></i> {{ $user->email ?? 'N/A' }}</p>
                    <p class="text-muted small mb-1"><i class="fas fa-phone me-1"></i> {{ $user->contact ?? 'N/A' }}</p>
                    <p class="text-muted small"><i class="fas fa-building me-1"></i> {{ $user->department ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-edit me-2"></i>Update Information
                </div>
                <div class="card-body">
                    <form method="POST" action="/student/profile">
                        @csrf
                        @method('PUT')

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required>
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact Number</label>
                                <input type="text" name="contact" class="form-control @error('contact') is-invalid @enderror"
                                    value="{{ old('contact', $user->contact) }}"
                                    placeholder="e.g., 09123456789">
                                @error('contact')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr>
                        <p class="text-muted small"><i class="fas fa-lock me-1"></i> Leave password fields blank to keep your current password.</p>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                    placeholder="Enter new password">
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm new password">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
