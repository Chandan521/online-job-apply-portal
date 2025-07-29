@extends('admin.layout.app')
@section('title', 'Admin - Job Management')
@section('content')
    <div class="page-header d-flex justify-content-between align-items-center">
        <h1 class="page-title">Job Management</h1>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i>Post New Job
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Recruiter</th>
                            <th>Location</th>
                            <th>Status</th>
                            <th>Is Approved</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($jobs as $job)
                            <tr>
                                <td>{{ $job->title }}</td>
                                <td>{{ $job->company }}</td>
                                <td>{{ $job->recruiter->name ?? '-' }}</td>
                                <td>{{ $job->location }}</td>
                                <td>
                                    <form action="{{ route('admin.jobs.toggleStatus', $job->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="btn btn-sm {{ $job->status ? 'btn-success' : 'btn-secondary' }}">
                                            {{ $job->status ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if ($job->is_approved)
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <div class="d-flex flex-column gap-2">
                                            <span class="badge bg-warning text-dark">Pending</span>

                                            <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success">
                                                    <i class="bi bi-check-circle me-1"></i> Approve
                                                </button>
                                            </form>

                                            <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to reject this job?');">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="bi bi-x-circle me-1"></i> Reject
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>


                                <td>
                                    <a href="{{ route('admin.jobs.show', $job->id) }}"
                                        class="btn btn-sm btn-primary">View</a>
                                    <a href="{{ route('admin.jobs.edit', $job->id) }}" class="btn btn-sm btn-info">Edit</a>
                                    <form action="{{ route('admin.jobs.destroy', $job->id) }}" method="POST"
                                        style="display:inline-block">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Are you sure?')"
                                            class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No jobs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $jobs->links() }}
        </div>
    </div>
@endsection
