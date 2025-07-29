@extends('admin.layout.app')

@section('title', 'Edit User')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left me-2"></i>Back to List
            </a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&size=128&background=random' }}"
                                    alt="{{ $user->name }}" class="img-fluid rounded-circle mb-3"
                                    style="width: 128px; height: 128px; object-fit: cover;">
                                <input type="file" name="profile_photo" id="profile_photo" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        value="{{ $user->name }}" required>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $user->email }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">Role</label>
                                    <select name="role" id="role" class="form-select" required>
                                        <option value="job_seeker" {{ $user->role == 'job_seeker' ? 'selected' : '' }}>Job
                                            Seeker</option>
                                        <option value="recruiter" {{ $user->role == 'recruiter' ? 'selected' : '' }}>
                                            Recruiter</option>
                                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" id="status" class="form-select" required>
                                        <option value="active" {{ $user->status == 'active' ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="inactive" {{ $user->status == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="banned" {{ $user->status == 'banned' ? 'selected' : '' }}>Banned
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 text-end">
                        <button type="submit" class="btn btn-primary">Update User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('user_permission_script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const permissionSection = document.getElementById('permissionSection');

            function togglePermissions() {
                permissionSection.style.display = (roleSelect.value === 'recruiter') ? 'block' : 'none';
            }

            roleSelect.addEventListener('change', togglePermissions);
            togglePermissions(); // Initial check
        });
    </script>
@endpush
