@extends('admin.layout.app') {{-- Or your custom admin layout --}}

@section('content')
<div class="container py-4">
    <h2>Create Blog Post</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label class="form-label">Title</label>
            <input name="title" type="text" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Content</label>
            <textarea name="content" id="editor" class="form-control">{{ old('content') }}</textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Featured Image</label>
            <input name="featured_image" type="file" class="form-control">
        </div>

        <button class="btn btn-primary">Publish</button>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
