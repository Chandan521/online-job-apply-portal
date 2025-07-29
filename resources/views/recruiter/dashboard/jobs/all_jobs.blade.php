@extends('recruiter.layout.dashboard_layout')

@section('title', 'My All Jobs')

@section('content')
    <div class="container mt-4">
        <h2 class="mb-4">All Jobs You Have Posted</h2>

        @if ($jobs->count())
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle text-center">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Location</th>
                            <th>Type</th>
                            <th>Is Approved</th>
                            <th>Shift</th>
                            <th>Review</th>
                            <th>Status</th>
                            <th>Viwes</th>
                            <th>Posted On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $index => $job)
                            <tr>
                                <td>{{ $index + $jobs->firstItem() }}</td>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->company }}</td>
                                <td>{{ $job->location }}</td>
                                <td>{{ $job->type }}</td>
                                <td>
                                    @if ($job->is_approved)
                                         <span class="badge bg-success">Approved</span>
                                    @else
                                       <span class="badge bg-warning"> Progress </span>
                                    @endif
                                </td>
                                <td>{{ $job->shift }}</td>
                                <td>@if ($job->reviews)
                                    {{ $job->reviews->count() }}
                                @endif</td>
                                <td>
                                    <span class="badge bg-{{ $job->status === 'active' ? 'success' : 'secondary' }}">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </td>
                                <td>{{ $job->views }}</td>
                                <td>{{ \Carbon\Carbon::parse($job->created_at)->format('d M Y') }}</td>
                                <td>
                                    <div class="d-flex flex-wrap justify-content-center gap-2">
                                        <a href="{{ route('recruiter.jobs.show', $job->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('recruiter.jobs.edit', $job->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('recruiter.jobs.destroy', $job->id) }}" method="POST"
                                            onsubmit="return confirm('Are you sure to delete this job?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                        <form action="{{ route('recruiter.jobs.toggleStatus', $job->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-sync-alt"></i>
                                                {{ $job->status === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="alert alert-info">You haven’t posted any jobs yet.</div>
        @endif
    </div>
@endsection

@section('footer')
    <footer class="mt-auto w-100 bg-light text-center py-3 text-muted small"
        style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 100; border-top: 1px solid #ddd;">
        © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">
            | Empowering recruiters to find top talent with ease.
        </span>
    </footer>
@endsection
