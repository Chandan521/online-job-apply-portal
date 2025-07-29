@extends('recruiter.layout.dashboard_layout')

@section('title', 'Edit Sub User')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Edit Sub User</h2>

    <form action="{{ route('recruiter.subuser.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" value="{{ $user->email }}" class="form-control" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">Permissions</label><br>
            @foreach ($availablePermissions as $perm)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="permissions[]"
                        id="perm_{{ $perm }}" value="{{ $perm }}"
                        {{ in_array($perm, $user->permissions ?? []) ? 'checked' : '' }}>
                    <label class="form-check-label" for="perm_{{ $perm }}">{{ ucwords(str_replace('_', ' ', $perm)) }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('recruiter.subuser.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
