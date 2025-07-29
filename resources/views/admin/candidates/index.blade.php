@extends('admin.layout.app')

@section('title', 'Admin - Candidates')

@section('page-title', 'Candidates')

@section('content')
<div class="page-header">
    <h1 class="page-title">Candidates</h1>
</div>

<div class="card">
    <div class="card-body">
        @if($candidates->count())
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Email</th>
                            <th>Applied Job</th>
                            <th>Status</th>
                            <th>Applied On</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($candidates as $application)
                            <tr>
                                <td>{{ $application->user->name ?? 'N/A' }}</td>
                                <td>{{ $application->user->email ?? 'N/A' }}</td>
                                <td>{{ $application->job->title ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-primary text-capitalize">
                                        {{ str_replace('_', ' ', $application->status) }}
                                    </span>
                                </td>
                                <td>{{ $application->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-muted">No candidates found.</p>
        @endif
    </div>
</div>
@endsection
