@extends('admin.layout.app')

@section('title', 'Admin - Profile Settings')

@section('page-title', 'Profile Settings')

@section('content')
    <div class="profile-settings-page">
        <div class="row">
            {{-- Left Column: Profile Card --}}
            <div class="col-xl-4 col-lg-5">
                <div class="card text-center">
                    <div class="card-body">
                        <img src="{{ auth()->user()->profile_photo ? asset('storage/' . auth()->user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&size=128&background=random' }}"
                            alt="Profile Photo" class="rounded-circle avatar-lg img-thumbnail" />
                        @php
                            // Cache the authenticated user object to avoid redundant calls.
                            $user = auth()->user();
                        @endphp

                        @if ($user)
                            <h4 class="mt-3 mb-0">{{ $user->name }}</h4>
                            <p class="text-success">{{ optional($user)->role }}</p>
                            <a href="{{ route('admin.profile.show') }}" class="btn btn-primary btn-sm mt-2">View Profile</a>
                        @else
                            <p class="text-danger mt-3">User not authenticated.</p>
                        @endif
                        @if (auth()->user()->profile_photo)
                            <form action="{{ route('admin.profile.remove_photo') }}" method="POST" class="mt-2">
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm">Remove Photo</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right Column: Settings Form with Tabs --}}
            <div class="col-xl-8 col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Edit Profile</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('POST')

                            {{-- Personal Information --}}
                            <h5 class="mb-4 text-primary">Personal Information</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', auth()->user()->name) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', auth()->user()->email) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control" id="phone" name="phone"
                                        value="{{ old('phone', auth()->user()->phone) }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="linkedin_url" class="form-label">LinkedIn URL</label>
                                    <input type="url" class="form-control" id="linkedin_url" name="linkedin_url"
                                        value="{{ old('linkedin_url', auth()->user()->linkedin_url) }}">
                                </div>
                            </div>

                            {{-- Location --}}
                            <h5 class="mb-4 text-primary">Location</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text" class="form-control" id="country" name="country"
                                        value="{{ old('country', auth()->user()->country) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city"
                                        value="{{ old('city', auth()->user()->city) }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address"
                                        value="{{ old('address', auth()->user()->address) }}">
                                </div>
                            </div>

                            {{-- Professional Info --}}
                            <h5 class="mb-4 text-primary">Professional Information</h5>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="about_me" class="form-label">About Me</label>
                                    <textarea class="form-control" id="about_me" name="about_me"
                                        rows="4">{{ old('about_me', auth()->user()->about_me) }}</textarea>
                                </div>
                            </div>

                            {{-- Documents --}}
                            <h5 class="mb-4 text-primary">Documents & Security</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="profile_photo" class="form-label">Profile Photo</label>
                                    <input class="form-control" type="file" id="profile_photo" name="profile_photo">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation">
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <style>
        .profile-settings-page .card {
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
        }

        .avatar-lg {
            height: 120px;
            width: 120px;
        }

        .form-label {
            font-weight: 600;
        }
    </style>
@endpush