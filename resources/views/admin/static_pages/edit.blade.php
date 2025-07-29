@extends('admin.layout.app')

@section('content')
    <div class="container py-4">
        <h2>Edit Static Page: {{ $page->title }}</h2>

        <form action="{{ route('admin.static_pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" class="form-control" value="{{ $page->title }}" disabled>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" id="editor" class="form-control" rows="10" required>{{ old('content', $page->content) }}</textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_visible" class="form-check-input" id="is_visible" value="1"
                    {{ old('is_visible', $page->is_visible) ? 'checked' : '' }}>

                <label class="form-check-label" for="is_visible">Visible to Public</label>
            </div>



            <button type="submit" class="btn btn-success">Update Page</button>
            <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>

        </form>
    </div>
@endsection
