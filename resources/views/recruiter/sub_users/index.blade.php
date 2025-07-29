@extends('recruiter.layout.dashboard_layout')

@section('title', 'Sub Users')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Manage Sub Users</h2>

    <a href="{{ route('recruiter.subuser.create') }}" class="btn btn-primary mb-3">Add Sub User</a>

    @if($subUsers->isEmpty())
        <div class="alert alert-info">No sub-users added yet.</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Permissions</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @foreach ($user->permissions ?? [] as $perm)
                                <span class="badge bg-secondary">{{ $perm }}</span>
                            @endforeach
                        </td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('recruiter.subuser.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Delete sub-user?')">
                                @csrf
                                @method('DELETE')
                                <a href="{{ route('recruiter.subuser.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

@section('footer')
<footer class="mt-auto w-100 bg-light text-center py-3 text-muted small"
        style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 100; border-top: 1px solid #ddd; transition: left 0.3s;">
    © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">
        | Empowering recruiters to find top talent with ease.
    </span>
</footer>
@endsection
