@extends('recruiter.layout.dashboard_layout')

@section('title', 'My Blogs')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>My Blogs</h3>
        <a href="{{ route('blog.create') }}" class="btn btn-primary">+ New Blog</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($blogs->isEmpty())
        <p class="text-muted">You haven’t posted any blogs yet.</p>
    @else
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Image</th>
                        <th>Posted At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $index => $blog)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $blog->title }}</td>
                            <td>{{ $blog->slug }}</td>
                            <td>
                                @if($blog->featured_image)
                                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="image" width="80">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>{{ $blog->created_at->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('blog.edit', $blog->id) }}" class="btn btn-sm btn-warning">Edit</a>

                                <form action="{{ route('blog.destroy', $blog->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this blog?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
@endsection
