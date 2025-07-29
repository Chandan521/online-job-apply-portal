@extends('admin.layout.app')

@section('title', 'Admin - My Profile')

@section('page-title', 'My Profile')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=128&background=random' }}"
                            alt="Profile Photo" class="rounded-circle avatar-lg img-thumbnail" />
                        <h4 class="mt-3 mb-0">{{ auth()->user()->name ?? 'N/A' }}</h4>
                        <p class="text-muted">{{ auth()->user()->role ?? 'N/A' }}</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.profile.index') }}" class="btn btn-primary btn-sm">Edit Profile</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Contact Information</h5>
                        <p class="card-text"><strong>Email:</strong> {{ auth()->user()->email ?? 'N/A' }}</p>
                        <p class="card-text"><strong>Phone:</strong> {{ auth()->user()->phone ?? 'N/A' }}</p>
                        <p class="card-text"><strong>LinkedIn:</strong> <a
                                href="{{ auth()->user()->linkedin_url ?? '#' }}"
                                target="_blank">{{ auth()->user()->linkedin_url ?? 'N/A' }}</a></p>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">About Me</h5>
                        <p>{{ auth()->user()->about_me ?? 'No information provided.' }}</p>

                        <hr>

                        <h5 class="card-title mb-3">Location</h5>
                        <p><strong>Address:</strong> {{ auth()->user()->address ?? 'N/A' }},
                            {{ auth()->user()->city ?? 'N/A' }}, {{ auth()->user()->country ?? 'N/A' }}</p>

                        <hr>

                        <h5 class="card-title mb-3">Professional Details</h5>
                        <p>
                            This section is not applicable for admin users.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <style>
        .avatar-lg {
            height: 120px;
            width: 120px;
        }
    </style>
@endpush