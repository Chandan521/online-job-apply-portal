<div>
    <form method="POST" action="{{ route('user.settings.profile.update') }}" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="mb-4 text-center">
            <div class="position-relative d-inline-block">
                <img id="profilePhotoPreview" src="{{ $user->profile_photo ? Storage::url($user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=0d6efd&color=fff&size=128' }}" class="rounded-circle border shadow" style="width: 110px; height: 110px; object-fit: cover;">
                <label for="profilePhotoInput" class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2" style="cursor:pointer;">
                    <i class="bi bi-camera text-white"></i>
                    <input type="file" name="profile_photo" id="profilePhotoInput" class="d-none" accept="image/*">
                </label>
            </div>
            <div class="form-text">Click the camera to change your profile photo</div>
        </div>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" class="form-control" name="name" value="{{ $user->name }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" class="form-control" name="phone" value="{{ $user->phone }}">
        </div>
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" class="form-control" name="city" value="{{ $user->city }}">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('profilePhotoInput');
    const preview = document.getElementById('profilePhotoPreview');
    input.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
