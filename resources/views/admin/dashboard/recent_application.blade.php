@extends('admin.layout.app')

@section('title', 'All Applications')

@section('content')
    <div class="container">

        <div class="card shadow-sm mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Applications</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Candidate</th>
                                <th>Email</th>
                                <th>Job</th>
                                <th>Status</th>
                                <th>Applied At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($applications as $app)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="{{ $app->user && $app->user->profile_photo
                                                ? asset('storage/' . $app->user->profile_photo)
                                                : 'https://ui-avatars.com/api/?name=' . urlencode($app->first_name . ' ' . $app->last_name) . '&background=random' }}"
                                                class="rounded-circle" alt="Profile" width="36" height="36"
                                                style="object-fit: cover;">
                                            <div>{{ $app->first_name }} {{ $app->last_name }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $app->email }}</td>
                                    <td>{{ $app->job->title ?? '—' }}</td>
                                    <td>
                                        @php
                                    $statusClass = '';
                                    switch ($app->status) {
                                        case 'submitted':
                                            $statusClass = 'badge-status-submitted'; // Gray
                                            break;
                                        case 'under_review':
                                            $statusClass = 'badge-status-under_review text-dark'; // Yellow
                                            break;
                                        case 'shortlisted':
                                            $statusClass = 'badge-status-shortlisted'; // Light Blue
                                            break;
                                        case 'interview':
                                            $statusClass = 'badge-status-interview'; // Blue
                                            break;
                                        case 'selected':
                                            $statusClass = 'badge-status-selected'; // Green
                                            break;
                                        case 'rejected':
                                            $statusClass = 'badge-status-rejected'; // Red
                                            break;
                                        case 'hired':
                                            $statusClass = 'badge-status-hired'; // Teal
                                            break;
                                        case 'withdrawn':
                                            $statusClass = 'badge-status-withdrawn'; // Purple
                                            break;
                                        default:
                                            $statusClass = 'bg-light text-dark'; // Fallback for unknown status
                                            break;
                                    }
                                @endphp
                                <span class="badge rounded-pill {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $app->status ?? 'New')) }}
                                </span>
                                    </td>
                                    <td>{{ $app->created_at->diffForHumans() }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No applications yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        {{ $applications->links() }}
    </div>
@endsection
