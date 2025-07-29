@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="fw-bold mb-4">Edit Profile</h4>
                    @include('components.alert')
                    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Country</label>
                            <input type="text" class="form-control" name="country" value="{{ old('country', $user->country) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" name="city" value="{{ old('city', $user->city) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" name="address" value="{{ old('address', $user->address) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Education</label>
                            <input type="text" class="form-control" name="education" value="{{ old('education', $user->education) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Experience</label>
                            <input type="text" class="form-control" name="experience" value="{{ old('experience', $user->experience) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">LinkedIn URL</label>
                            <input type="url" class="form-control" name="linkedin_url" value="{{ old('linkedin_url', $user->linkedin_url) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">About Me</label>
                            <textarea class="form-control" name="about_me" rows="3">{{ old('about_me', $user->about_me) }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Skills (comma separated)</label>
                            <input type="text" class="form-control" name="skills" value="{{ old('skills', $user->skills) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Resume (PDF)</label>
                            <input type="file" class="form-control" name="resume" accept="application/pdf">
                            @if($user->resume)
                                <a href="{{ asset('storage/'.$user->resume) }}" target="_blank" class="d-block mt-2">View Current Resume</a>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Photo</label>
                            <input type="file" class="form-control" name="profile_photo" accept="image/*">
                            @if($user->profile_photo)
                                <img src="{{ asset('storage/'.$user->profile_photo) }}" alt="Profile Photo" class="img-thumbnail mt-2" style="max-width: 120px;">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
