@extends('admin.layout.app')

@section('title', 'User Details')
@section('page-title', 'User Details')

@push('view_user_style')
    <style>
        /* General Admin Card and List Styling */
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        .card-body {
            color: var(--bs-dark);
            background-color: var(--bs-light);
        }

        .card-header {
            background-color: var(--bs-dark);
            border-bottom: 1px solid var(--bs-border-color);
            font-weight: 600;
            color: var(--bs-light);
            padding: 1rem 1.5rem;
            /* Standardized padding */
        }

        .list-group-item {
            border-color: var(--bs-border-color-translucent);
            background-color: var(--bs-body-bg);
            display: flex;
            /* Use flexbox for aligned key-value pairs */
            align-items: center;
            padding: 0.75rem 1.25rem;
            /* Standardized padding */
        }

        .list-group-item:first-child {
            border-top-left-radius: 0.375rem;
            border-top-right-radius: 0.375rem;
        }

        .list-group-item:last-child {
            border-bottom-left-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
        }


        .list-group-item strong {
            min-width: 120px;
            /* Adjust as needed for labels */
            display: inline-block;
            color: var(--bs-info);
            font-weight: 600;
            margin-right: 0.75rem;
            /* Space between strong text and value */
        }

        /* Role Badge Styling */
        .badge-role {
            padding: 0.5em 0.8em;
            font-size: 0.8em;
            font-weight: 700;
            letter-spacing: 0.05em;
        }

        .badge-role-admin {
            background-color: var(--bs-danger);
        }

        .badge-role-recruiter {
            background-color: var(--bs-info);
        }

        .badge-role-recruiter_assistant {
            background-color: var(--light-color);
        }

        /* Bootstrap info is good for recruiter */
        .badge-role-job_seeker {
            background-color: var(--bs-primary);
        }

        /* Primary color for job seeker */

        /* Table Styling for Job Applications */
        .table thead th {
            background-color: var(--bs-dark);
            color: var(--bs-white);
            border-color: var(--bs-gray-700);
        }

        .table-responsive {
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--bs-border-color);
            /* Add a subtle border around the table wrapper */
        }

        .table {
            margin-bottom: 0;
            /* Remove default table margin */
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: var(--bs-table-striped-bg);
        }

        .table-hover tbody tr:hover {
            background-color: var(--bs-table-hover-bg);
        }

        /* Application Status Badges (reusing colors from Job Details) */
        .badge-status-submitted {
            background-color: #6c757d;
        }

        /* Gray */
        .badge-status-under_review {
            background-color: #ffc107;
            color: #343a40;
        }

        /* Warning - Yellow */
        .badge-status-shortlisted {
            background-color: #0dcaf0;
        }

        /* Info - Light Blue */
        .badge-status-interview {
            background-color: #0d6efd;
        }

        /* Primary - Blue */
        .badge-status-selected {
            background-color: #198754;
        }

        /* Success - Green */
        .badge-status-rejected {
            background-color: #dc3545;
        }

        /* Danger - Red */
        .badge-status-hired {
            background-color: #20c997;
        }

        /* Teal */
        .badge-status-withdrawn {
            background-color: #6f42c1;
        }

        /* Purple */

        .badge {
            font-size: 0.85em;
            /* Slightly larger for better readability */
            padding: 0.4em 0.7em;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
        <h1 class="h2 mb-0 text-primary">User Details: {{ $user->name }}</h1>
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg">
            <i class="bi bi-arrow-left me-2"></i> Back to Users
        </a>
    </div>



    {{-- User Basic Info --}}
    <div class="card mb-4">
        <div class="card-header">
            Basic Information
        </div>
        <div class="card-body p-0"> {{-- Remove card-body padding if list-group-flush is used directly within --}}
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Name:</strong> {{ $user->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> <a href="mailto:{{ $user->email }}"
                        class="text-decoration-none">{{ $user->email }}</a></li>
                <li class="list-group-item">
                    <strong>Role:</strong>
                    @php
                        $roleClass = '';
                        switch ($user->role) {
                            case 'admin':
                                $roleClass = 'badge-role-admin';
                                break;
                            case 'recruiter':
                                $roleClass = 'badge-role-recruiter';
                                break;
                            case 'recruiter_assistant':
                                $roleClass = 'badge-role-recruiter_assistant';
                                break;

                            case 'job_seeker':
                                $roleClass = 'badge-role-job_seeker';
                                break;
                            default:
                                $roleClass = 'bg-secondary'; // Fallback for unknown roles
                                break;
                        }
                    @endphp
                    <span
                        class="badge rounded-pill text-uppercase {{ $roleClass }} badge-role">{{ str_replace('_', ' ', $user->role) }}</span>
                </li>
                @if (!empty($user->permissions) && count($user->permissions))
                    <li class="list-group-item">
                        <strong>Permissions:</strong>
                        @foreach ($user->permissions as $permission)
                            <span class="badge bg-secondary">{{ $permission }}</span>
                        @endforeach
                    </li>
                @endif

                <li class="list-group-item"><strong>Joined On:</strong> {{ $user->created_at->format('d M Y, h:i A') }}</li>
            </ul>
        </div>
    </div>



    @if ($user->role === 'job_seeker')
        {{-- Job Applications --}}
        <div class="card mb-4">
            <div class="card-header">
                Job Applications ({{ $user->jobApplications->count() }})
            </div>
            <div class="card-body">
                @if ($user->jobApplications->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Job Title</th>
                                    <th scope="col">Company</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Applied On</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->jobApplications as $index => $app)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($app->job)
                                                <a href="{{ route('admin.jobs.show', $app->job->id) }}"
                                                    class="text-primary text-decoration-none">
                                                    {{ $app->job->title }}
                                                </a>
                                            @else
                                                N/A (Job Deleted)
                                            @endif
                                        </td>
                                        <td>{{ $app->job->company ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge rounded-pill text-uppercase badge-status-{{ $app->status }}">
                                                {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                            </span>
                                        </td>
                                        <td>{{ $app->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            @if ($app->job)
                                                <a href="{{ route('admin.jobs.show', $app->job->id) }}"
                                                    class="btn btn-sm btn-outline-primary" title="View Job Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-info-circle me-2"></i> This job seeker has not submitted any job applications yet.
                    </div>
                @endif
            </div>
        </div>
    @elseif($user->role === 'recruiter')
        {{-- Jobs Posted by Recruiter --}}
        <div class="card mb-4">
            <div class="card-header">
                Jobs Posted ({{ $user->jobs->count() }})
            </div>
            <div class="card-body">
                @if ($user->jobs->count())
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle text-nowrap">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Job Title</th>
                                    <th scope="col">Location</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Posted On</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->jobs as $index => $job)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.jobs.show', $job->id) }}"
                                                class="text-primary text-decoration-none">
                                                {{ $job->title }}
                                            </a>
                                        </td>
                                        <td>{{ $job->location ?? 'N/A' }}</td>
                                        <td><span class="badge bg-success">{{ ucfirst($job->status ?? 'active') }}</span>
                                        </td>
                                        <td>{{ $job->created_at->format('d M Y, h:i A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.jobs.show', $job->id) }}"
                                                class="btn btn-sm btn-outline-primary" title="View Job Details">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-info text-center" role="alert">
                        <i class="bi bi-info-circle me-2"></i> This recruiter has not posted any jobs yet.
                    </div>
                @endif
            </div>

        </div>
    @endif
@endsection
