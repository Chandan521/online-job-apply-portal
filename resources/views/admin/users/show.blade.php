@extends('admin.layout.app')

@section('title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Back to List
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow-sm mb-4">
                <div class="card-body text-center">
                    <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=100&background=random' }}" alt="{{ $user->name }}" class="rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                    <h5 class="card-title mb-0">{{ $user->name }}</h5>
                    <p>{{ $user->email }}</p>
                    <div>
                        @php
                            $roleBadge = 'bg-secondary';
                            if ($user->role === 'admin') $roleBadge = 'bg-primary';
                            if ($user->role === 'recruiter') $roleBadge = 'bg-success';
                            if ($user->role === 'job_seeker') $roleBadge = 'bg-info';
                            if ($user->role === 'recruiter_assistant') $roleBadge = 'bg-success-subtle';
                        @endphp
                        <span class="badge {{ $roleBadge }}">{{ $user->role }}</span>

                        @php
                            $statusBadge = 'bg-secondary';
                            if ($user->status === 'active') $statusBadge = 'bg-success';
                            if ($user->status === 'inactive') $statusBadge = 'bg-warning';
                            if ($user->status === 'banned') $statusBadge = 'bg-danger';
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ $user->status }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-4">Additional Information</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <strong>Joined Date</strong>
                            <span>{{ $user->created_at->format('M d, Y') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <strong>Last Login</strong>
                            <span>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <strong>Last Login IP</strong>
                            <span>{{ $user->last_login_ip ?? 'N/A' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection