@extends('admin.layout.app')

@section('title', 'Admin - Dashboard')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Blogs</h2>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add New Blog
        </a>
    </div>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Title</th>
                <th>Slug</th>
                <th>Image</th>
                <th>Author</th>
                <th>Updated</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse($blogs as $blog)
                <tr>
                    <td>{{ $blog->title }}</td>
                    <td>{{ $blog->slug }}</td>
                    <td>
                        @if ($blog->featured_image && Storage::disk('public')->exists($blog->featured_image))
                            <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured Image"
                                style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>
                    <td>
                        @if ($blog->user_type === 'admin' && $blog->admin)
                            Admin: {{ $blog->admin->name }}
                        @elseif ($blog->user)
                            {{ ucfirst($blog->user->role) }}: {{ $blog->user->name }}
                        @else
                            <span class="text-muted">Unknown</span>
                        @endif
                    </td>
                    <td>{{ $blog->updated_at->diffForHumans() }}</td>
                    <td class="text-nowrap">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="btn btn-sm btn-secondary me-1"
                            title="View" target="_blank">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-primary me-1"
                            title="Edit">
                            <i class="bi bi-pencil-square"></i>
                        </a>
                        <form action="{{ route('admin.blog.destroy', $blog->id) }}" method="POST"
                            style="display: inline-block;"
                            onsubmit="return confirm('Are you sure you want to delete this blog?');">
                            @csrf
                            @method('DELETE') {{-- ✅ This line was missing --}}
                            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">No blogs found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $blogs->links() }}
    </div>
</div>
@endsection
