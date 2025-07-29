@extends('recruiter.layout.dashboard_layout')

@section('title', 'Edit Blog')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Edit Blog</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Please fix the following issues:
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>- {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Blog Title</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" id="tinymce-editor" class="form-control" rows="10">{{ old('content', $blog->content) }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Current Featured Image</label>
                @if ($blog->featured_image)
                    <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Current Image"
                        style="max-width: 200px; border: 1px solid #ccc; padding: 5px;">
                @else
                    <p class="text-muted">No image uploaded.</p>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Change Featured Image (optional)</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*"
                    onchange="previewFeaturedImage(event)">
            </div>

            <div class="mb-3" id="image-preview-container" style="display: none;">
                <label class="form-label d-block">New Image Preview:</label>
                <img id="image-preview" src="" alt="Preview"
                    style="max-width: 250px; border: 1px solid #ccc; padding: 5px;">
            </div>

            <button type="submit" class="btn btn-success">Update Blog</button>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection

