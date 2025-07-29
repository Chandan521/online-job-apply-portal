@extends('admin.layout.app')

@section('title', 'Manage Applications')
@section('page-title', 'Application Management')

@push('job_application_styles')

@endpush

@section('content')
<div class="mb-4">
    <canvas id="statusChart" height="50"></canvas>
</div>

<form method="GET" class="row g-3 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by candidate or job">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">-- Filter by Status --</option>
            @foreach(['submitted','under_review','shortlisted','interview','selected','rejected','hired','withdrawn'] as $status)
                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Search</button>
    </div>
</form>

<div class="card">
    <div class="card-body table-responsive">
        @if($applications->count())
        <table class="table table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Candidate</th>
                    <th>Job Title</th>
                    <th>Recruiter</th>
                    <th>Status</th>
                    <th>Applied At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($applications as $index => $application)
                <tr>
                    <td>{{ $applications->firstItem() + $index }}</td>
                    <td>{{ $application->user->name ?? 'N/A' }}</td>
                    <td>{{ $application->job->title ?? 'N/A' }}</td>
                    <td>{{ $application->job->recruiter->name ?? 'N/A' }}</td>
                    <td>@php
    $statusClass = '';
    switch ($application->status) {
        case 'submitted':
            $statusClass = 'bg-secondary'; // Gray
            break;
        case 'under_review':
            $statusClass = 'bg-warning text-dark'; // Yellow with dark text
            break;
        case 'shortlisted':
            $statusClass = 'bg-info'; // Light Blue
            break;
        case 'interview':
            $statusClass = 'bg-primary'; // Blue
            break;
        case 'selected':
            $statusClass = 'bg-success'; // Green
            break;
        case 'rejected':
            $statusClass = 'bg-danger'; // Red
            break;
        case 'hired':
            $statusClass = 'bg-success'; // Can use a slightly different shade if available, or just success
            break;
        case 'withdrawn':
            $statusClass = 'bg-dark'; // Dark for withdrawn
            break;
        default:
            $statusClass = 'bg-light text-dark'; // Fallback for unknown status
            break;
    }
@endphp
<span class="badge rounded-pill {{ $statusClass }}">
    {{ ucfirst(str_replace('_', ' ', $application->status ?? 'N/A')) }}
</span></span></td>
                    <td>{{ $application->created_at->format('d M Y') }}</td>
                    <td>
                        <a href="{{ route('admin.jobs.show', $application->job_id) }}" class="btn btn-sm btn-outline-primary">View Job</a>
                        <a href="{{ route('admin.users.show', $application->user_id) }}" class="btn btn-sm btn-outline-success">View Candidate</a>
                        <a href="{{ route('admin.recruiters.show', $application->job->recruiter_id ?? 0) }}" class="btn btn-sm btn-outline-secondary">View Recruiter</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{ $applications->links() }}
        @else
        <div class="alert alert-info text-center">No applications found.</div>
        @endif
    </div>
</div>
@endsection

@push('job_application_script')
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_keys($chartData)) !!},
            datasets: [{
                label: '# of Applications',
                data: {!! json_encode(array_values($chartData)) !!},
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
