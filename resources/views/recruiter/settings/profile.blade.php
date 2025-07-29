<div class="container py-5">
    <h2 class="mb-4 text-center">Update Profile</h2>

    <form action="{{ route('recruiter.profile.update') }}" method="POST" enctype="multipart/form-data" class="row g-4">
        @csrf
        @method('PATCH')

        <!-- Profile Image -->
        <div class="d-flex justify-content-center">
            <div class="position-relative" style="width: 130px; height: 130px;">
                <img src="{{ Auth::user()->profile_photo ? asset('storage/' . Auth::user()->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=6c757d&color=fff&size=128' }}"
                    class="rounded-circle shadow border" style="width: 130px; height: 130px; object-fit: cover;"
                    id="avatarPreview">

                <!-- Camera Icon for Upload -->
                <label for="profile_photo"
                    class="position-absolute bottom-0 end-0 bg-white border rounded-circle p-2 shadow-sm"
                    style="cursor: pointer;">
                    <i class="fas fa-camera text-dark"></i>
                    <input type="file" name="profile_photo" id="profile_photo" class="d-none" accept="image/*"
                        onchange="previewAvatar(event)">
                </label>
            </div>
        </div>

        @if (Auth::user()->profile_photo)
            <div class="text-center text-muted small">Change Profile Image</div>
        @endif

        <!-- Name -->
        <div class="col-md-6">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', Auth::user()->name) }}" placeholder="{{ Auth::user()->name ?? 'Not Set' }}">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="col-md-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', Auth::user()->email) }}" placeholder="{{ Auth::user()->email ?? 'Not Set' }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Phone -->
        <div class="col-md-6">
            <label for="phone" class="form-label">Phone Number</label>
            <input type="text" id="phone" name="phone" class="form-control"
                value="{{ old('phone', Auth::user()->phone) }}"
                placeholder="{{ Auth::user()->phone ?? 'Set Mobile Number' }}">
        </div>

        <!-- Role -->
        @if (auth()->user()->role !== 'recruiter')
            {{-- Only show for admin-level users --}}
            <div class="col-md-6 d-none">
                <label for="role" class="form-label">Account Type</label>
                <select name="role" id="role" class="form-select">
                    <option value="recruiter" {{ Auth::user()->role === 'recruiter' ? 'selected' : '' }}>Recruiter
                    </option>
                    <option value="jobseeker" {{ Auth::user()->role === 'jobseeker' ? 'selected' : '' }}>Job Seeker
                    </option>
                </select>
            </div>
        @endif


        <!-- Country -->
        <div class="col-md-6">
            <label for="country" class="form-label">Country</label>
            <input type="text" id="country" name="country" class="form-control"
                value="{{ old('country', Auth::user()->country) }}"
                placeholder="{{ Auth::user()->country ?? 'Not Set' }}">
        </div>

        <!-- City -->
        <div class="col-md-6">
            <label for="city" class="form-label">City</label>
            <input type="text" id="city" name="city" class="form-control"
                value="{{ old('city', Auth::user()->city) }}" placeholder="{{ Auth::user()->city ?? 'Not Set' }}">
        </div>

        <!-- Address -->
        <div class="col-12">
            <label for="address" class="form-label">Address</label>
            <textarea id="address" name="address" rows="2" class="form-control"
                placeholder="{{ Auth::user()->address ?? 'Not Set' }}">{{ old('address', Auth::user()->address) }}</textarea>
        </div>

        <!-- LinkedIn -->
        <div class="col-12">
            <label for="linkedin" class="form-label">LinkedIn URL</label>
            <input type="url" id="linkedin_url" name="linkedin_url" class="form-control"
                value="{{ old('linkedin_url', Auth::user()->linkedin_url) }}"
                placeholder="{{ Auth::user()->linkedin_url ?? 'Not Set' }}">
        </div>

        <!-- About Me -->
        <div class="col-12">
            <label for="about_me" class="form-label">About Me</label>
            <textarea id="about_me" name="about_me" rows="4" class="form-control"
                placeholder="{{ Auth::user()->about_me ?? 'Tell us about yourself...' }}">{{ old('about_me', Auth::user()->about_me) }}</textarea>
        </div>

        <!-- Save -->
        <div class="col-12 text-end">
            <button type="submit" class="btn btn-primary px-4">Update Profile</button>
        </div>
    </form>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf
    </form>
</div>
