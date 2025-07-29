@extends('recruiter.layout.dashboard_layout')

@section('title', 'Create Blog')

@section('content')
<div class="container py-4">
    <h2 class="mb-4">Create New Blog</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please fix the following issues:<br>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Blog Title</label>
            <input type="text" name="title" class="form-control" placeholder="Enter blog title" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="tinymce-editor" class="form-control" rows="10">{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Featured Image (optional)</label>
            <input type="file" name="featured_image" class="form-control" accept="image/*" onchange="previewFeaturedImage(event)">
        </div>

        <div class="mb-3" id="image-preview-container" style="display: none;">
            <label class="form-label d-block">Preview:</label>
            <img id="image-preview" src="" alt="Preview" style="max-width: 250px; border: 1px solid #ccc; padding: 5px;">
        </div>

        <button type="submit" class="btn btn-primary">Publish Blog</button>
        <a href="{{ route('blog.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

