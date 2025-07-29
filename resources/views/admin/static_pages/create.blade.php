@extends('admin.layout.app')

@section('content')
    <div class="container py-4">
        <h2 class="mb-4">Add Static Page</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.static_pages.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" id="editor" class="form-control" rows="10"></textarea>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="is_visible" class="form-check-input" id="is_visible" value="1"
       {{ old('is_visible') ? 'checked' : '' }}>

                <label class="form-check-label" for="is_visible">Visible to Public</label>
            </div>


            <button type="submit" class="btn btn-primary">Create Page</button>
        </form>
    </div>
@endsection

