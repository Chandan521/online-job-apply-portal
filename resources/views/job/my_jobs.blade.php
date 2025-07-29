@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h2 class="mb-4">My Job Applications</h2>
    @if($applications->isEmpty())
        <div class="alert alert-info text-center">
            You haven't applied to any jobs yet.<br>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Apply for a Job</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Job Title</th>
                        <th>Company</th>
                        <th>Applied On</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr>
                            <td>{{ $app->job->title ?? '-' }}</td>
                            <td>{{ $app->job->company ?? '-' }}</td>
                            <td>{{ $app->created_at->format('Y-m-d') }}</td>
                            <td><span class="badge bg-success">Submitted</span></td>
                            <td>
                                @if($app->job)
                                    <a href="{{ route('job.full', $app->job->id) }}" class="btn btn-sm btn-primary">View Job</a>
                                @else
                                    <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
