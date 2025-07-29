@extends('recruiter.layout.dashboard_layout')

@section('title', 'Add Sub User')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">Add Sub User</h2>

        <form action="{{ route('recruiter.subuser.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                <input type="email" name="email" id="email" class="form-control" required
                    value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            @php
                $availablePermissions = [
                    'create_job' => 'Create Job',
                    'view_applied_users' => 'Applied User',
                    'manage_all_jobs' => 'Manage All Job',
                    'manage_applications' => 'Manage Application',
                    'manage_team' => 'Manage Team',
                    'manage_blog' => 'Manage Blogs',
                    'manage_interview' => 'Manage Interview'
                ];
            @endphp

            <div class="mb-3">
                <label class="form-label">Permissions <span class="text-danger">*</span></label><br>
                @foreach ($availablePermissions as $permKey => $permLabel)
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permKey }}"
                            id="perm_{{ $permKey }}">
                        <label class="form-check-label" for="perm_{{ $permKey }}">{{ $permLabel }}</label>
                    </div>
                @endforeach
            </div>


            <button type="submit" class="btn btn-success">Create Sub User</button>
            <a href="{{ route('recruiter.subuser.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
