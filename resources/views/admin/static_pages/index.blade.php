@extends('admin.layout.app')

@section('title', 'Static Pages')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Static Pages</h2>
        <a href="{{ route('admin.static_pages.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Add New Page
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Page Title</th>
                    <th>Slug</th>
                    <th>Last Updated</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>{{ $page->slug }}</td>
                        <td>{{ $page->updated_at->diffForHumans() }}</td>
                        <td class="text-nowrap text-center">
                            <a href="{{ route('static_pages.show', $page->id) }}" class="btn btn-sm btn-secondary me-1" title="View">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('admin.static_pages.edit', $page->id) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route('static_pages.destroy', $page->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this page?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">No static pages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $pages->links() }}
    </div>
</div>
@endsection
