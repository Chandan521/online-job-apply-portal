@section('title', 'Seetings')

@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="row g-4">
                <!-- Sidebar Navigation -->
                <div class="col-md-4">
                    <div class="card shadow border-0 mb-4 animate__animated animate__fadeInLeft bg-white">
                        <div class="card-body p-0">
                            <div class="text-center py-4 border-bottom bg-light">
                                <img src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff&size=128' }}" class="rounded-circle border shadow" style="width: 80px; height: 80px; object-fit: cover;">
                                <div class="fw-bold mt-2">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                            </div>
                            <ul class="list-group list-group-flush settings-nav">
                                <li class="list-group-item border-0 ps-4 @if($tab === 'profile') active @endif">
                                    <a href="?tab=profile" class="d-flex align-items-center py-3 @if($tab === 'profile') fw-bold text-primary @endif"><i class="bi bi-person me-2"></i>Profile</a>
                                </li>
                                <li class="list-group-item border-0 ps-4 @if($tab === 'resume') active @endif">
                                    <a href="?tab=resume" class="d-flex align-items-center py-3 @if($tab === 'resume') fw-bold text-primary @endif"><i class="bi bi-file-earmark-pdf me-2"></i>Resume</a>
                                </li>
                                <li class="list-group-item border-0 ps-4 @if($tab === 'reviews') active @endif">
                                    <a href="?tab=reviews" class="d-flex align-items-center py-3 @if($tab === 'reviews') fw-bold text-primary @endif"><i class="bi bi-star me-2"></i>My Reviews</a>
                                </li>
                                <li class="list-group-item border-0 ps-4 @if($tab === 'jobs') active @endif">
                                    <a href="?tab=jobs" class="d-flex align-items-center py-3 @if($tab === 'jobs') fw-bold text-primary @endif"><i class="bi bi-briefcase me-2"></i>My Jobs</a>
                                </li>
                                <li class="list-group-item border-0 ps-4 @if($tab === 'security') active @endif">
                                    <a href="?tab=security" class="d-flex align-items-center py-3 @if($tab === 'security') fw-bold text-primary @endif"><i class="bi bi-shield-lock me-2"></i>Security</a>
                                </li>
                                <li class="list-group-item border-0 ps-4 @if($tab === 'account') active @endif">
                                    <a href="?tab=account" class="d-flex align-items-center py-3 @if($tab === 'account') fw-bold text-primary @endif"><i class="bi bi-gear me-2"></i>Account</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- Main Content -->
                <div class="col-md-8">
                    <div class="card shadow border-0 animate__animated animate__fadeIn bg-white">
                        <div class="card-body">
                            @if($tab === 'profile')
                                @include('user_settings.settings_profile', ['user' => $user])
                            @elseif($tab === 'resume')
                                @include('user_settings.settings_resume', ['user' => $user])
                            @elseif($tab === 'reviews')
                                @include('user_settings.settings_reviews', ['user' => $user])
                            @elseif($tab === 'jobs')
                                @include('user_settings.settings_jobs', ['user' => $user])
                            @elseif($tab === 'security')
                                @include('user_settings.settings_security', ['user' => $user])
                            @elseif($tab === 'account')
                                @include('user_settings.settings_account', ['user' => $user])
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.settings-nav .list-group-item.active,
.settings-nav .list-group-item:has(a.fw-bold.text-primary) {
    background: linear-gradient(90deg, #e3f0ff 0%, #f8fbff 100%);
    border-left: 4px solid #0d6efd;
}
.settings-nav .list-group-item a {
    color: #333;
    text-decoration: none;
    transition: color 0.2s;
}
.settings-nav .list-group-item a:hover {
    color: #0d6efd;
}
</style>
@endsection
{{-- Footer Section --}}
@section('footer')
<footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
    © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
</footer>
@endsection

