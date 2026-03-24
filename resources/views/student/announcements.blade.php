@extends('layouts.student')

@section('title', 'Announcements')

@section('content')
<div class="container-fluid">
    <h2><i class="fas fa-bullhorn"></i> Announcements</h2>

    @if($announcements->isEmpty())
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No announcements at this time. Check back later.
    </div>
    @else
    <div class="row g-3">
        @foreach($announcements as $ann)
        <div class="col-12">
            <div class="card" style="cursor:pointer;" onclick="document.getElementById('annModal{{ $ann->id }}').querySelector('[data-bs-toggle]') && new bootstrap.Modal(document.getElementById('annModal{{ $ann->id }}')).show()">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span class="fw-bold"><i class="fas fa-megaphone me-2" style="color:var(--ojtms-accent);"></i>{{ $ann->title }}</span>
                    <small class="text-muted"><i class="fas fa-calendar me-1"></i>{{ $ann->created_at->format('F d, Y') }}</small>
                </div>
                <div class="card-body">
                    <p class="mb-2" style="white-space: pre-wrap; line-height: 1.7;">{{ Str::limit($ann->content, 200) }}</p>
                    <div class="d-flex justify-content-between align-items-center">
                        @if($ann->creator)
                        <small class="text-muted">
                            <i class="fas fa-user me-1"></i>{{ $ann->creator->first_name }} {{ $ann->creator->last_name }}
                        </small>
                        @endif
                        @if(Str::length($ann->content) > 200)
                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#annModal{{ $ann->id }}">
                            <i class="fas fa-expand-alt me-1"></i>Read More
                        </button>
                        @else
                        <button class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#annModal{{ $ann->id }}">
                            <i class="fas fa-eye me-1"></i>View
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Full Detail Modal --}}
        <div class="modal fade" id="annModal{{ $ann->id }}" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header" style="background:var(--ojtms-primary); color:white;">
                        <h5 class="modal-title"><i class="fas fa-bullhorn me-2" style="color:var(--ojtms-accent);"></i>{{ $ann->title }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex flex-wrap gap-3 text-muted small mb-3">
                            @if($ann->creator)
                            <span><i class="fas fa-user me-1"></i>Posted by <strong>{{ $ann->creator->first_name }} {{ $ann->creator->last_name }}</strong></span>
                            @endif
                            <span><i class="fas fa-calendar me-1"></i>{{ $ann->created_at->format('F d, Y \a\t g:i A') }}</span>
                            @if($ann->updated_at->gt($ann->created_at->addMinute()))
                            <span><i class="fas fa-pencil-alt me-1"></i>Edited {{ $ann->updated_at->format('M d, Y g:i A') }}</span>
                            @endif
                        </div>
                        <hr>
                        <div style="white-space: pre-wrap; line-height: 1.8; font-size: 15px;">{{ $ann->content }}</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
