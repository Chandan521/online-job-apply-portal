@extends('admin.layout.app')

@section('content')
<div class="container py-4">
    <h2>Edit Blog Page: {{ $blog->title }}</h2>

    <form action="{{ route('admin.blog.update', $blog->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $blog->title) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="editor" class="form-control">{{ old('content', $blog->content) }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Current Featured Image</label><br>
            @if($blog->featured_image)
                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Current Featured Image" class="img-thumbnail mb-2" style="max-height: 120px;">
            @else
                <p class="text-muted">No image uploaded.</p>
            @endif
        </div>

        <div class="mb-3">
            <label class="form-label">Upload New Featured Image</label>
            <input name="featured_image" type="file" class="form-control" onchange="previewImage(event)">
        </div>

        <div class="mb-3">
            <label class="form-label">Preview</label><br>
            <img id="preview" src="#" alt="Image Preview" class="img-thumbnail" style="display: none; max-height: 120px;">
        </div>

        <button type="submit" class="btn btn-success">Update Blog</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>

{{-- Image preview script --}}
<script>
    function previewImage(event) {
        const [file] = event.target.files;
        if (file) {
            const preview = document.getElementById('preview');
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    }
</script>
@endsection
