@extends('admin.layout.app')

@section('title', 'Recruiter Profile')
@section('page-title', 'Recruiter Details')

@push('view_recruiter_styles')
<style>
    /* General Admin Card Styling */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: var(--bs-dark);
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
        color: var(--bs-light);
        padding: 1rem 1.5rem;
    }

    /* Profile Header Section */
    .profile-header {
        display: flex;
        align-items: center;
        padding: 1.5rem;
        background-color: var(--bs-white);
        border-bottom: 1px solid var(--bs-border-color-translucent);
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }

    .profile-photo-wrapper {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
        margin-right: 1.5rem;
        border: 3px solid var(--bs-primary);
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: var(--bs-gray-200); /* For default avatar */
    }

    .profile-photo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .profile-initials {
        font-size: 3rem;
        font-weight: bold;
        color: var(--bs-white);
        text-align: center;
        line-height: 1;
        background-color: var(--bs-primary);
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .profile-info h4 {
        margin-bottom: 0.25rem;
        color: var(--bs-dark);
    }
    .profile-info .text-muted {
        font-size: 1.05rem;
        margin-bottom: 0.75rem;
    }

    /* Details List Styling */
    .details-list {
        padding: 0 1.5rem 1.5rem 1.5rem;
        list-style: none; /* Remove default list style */
    }
    .details-list li {
        display: flex;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--bs-border-color-translucent); /* Subtle separator */
    }
    .details-list li:last-child {
        border-bottom: none;
    }
    .details-list strong {
        min-width: 150px; /* Aligns the labels */
        display: inline-block;
        color: var(--bs-light);
        font-weight: 600;
        margin-right: 0.75rem;
    }
    .details-list a {
        color: var(--bs-primary);
        text-decoration: none;
    }
    .details-list a:hover {
        text-decoration: underline;
    }

    /* Badges */
    .badge {
        padding: 0.5em 0.8em;
        font-size: 0.85em;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .badge-status-active { background-color: var(--bs-success); }
    .badge-status-inactive { background-color: var(--bs-secondary); }
    .badge-job-status-active { background-color: var(--bs-success); } /* For job status */
    .badge-job-status-inactive { background-color: var(--bs-secondary); } /* For job status */

    /* Table Styling for Jobs Posted */
    .table thead th {
        background-color: var(--bs-dark);
        color: var(--bs-white);
        border-color: var(--bs-gray-700);
    }
    .table-responsive {
        border-radius: 0.5rem;
        overflow: hidden;
        border: 1px solid var(--bs-border-color);
    }
    .table {
        margin-bottom: 0;
    }
    .table-striped tbody tr:nth-of-type(odd) {
        background-color: var(--bs-table-striped-bg);
    }
    .table-hover tbody tr:hover {
        background-color: var(--bs-table-hover-bg);
    }
    .table td, .table th {
        vertical-align: middle; /* Align content in middle of cells */
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h1 class="h2 mb-0 text-primary">Recruiter Profile</h1>
    <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg">
        <i class="bi bi-arrow-left me-2"></i> Back to Recruiters
    </a>
</div>



{{-- Recruiter Profile Card --}}
<div class="card mb-4">
    <div class="profile-header">
        {{-- Profile Picture --}}
        <div class="profile-photo-wrapper">
            @if($recruiter->profile_photo)
                <img src="{{ asset('storage/' . $recruiter->profile_photo) }}" class="profile-photo" alt="Profile Photo">
            @else
                <div class="profile-initials">
                    {{ strtoupper(substr($recruiter->name, 0, 1)) }}
                </div>
            @endif
        </div>

        {{-- Profile Info (Header Section) --}}
        <div class="profile-info">
            <h4>{{ $recruiter->name ?? 'N/A' }}</h4>
            <p class="text-muted mb-0">
                <i class="bi bi-envelope me-2"></i>
                <a href="mailto:{{ $recruiter->email }}" class="text-muted text-decoration-none">{{ $recruiter->email ?? 'N/A' }}</a>
            </p>
            <p class="text-muted">
                <i class="bi bi-person-fill me-2"></i>Recruiter ID: <span class="fw-bold">{{ $recruiter->id }}</span>
            </p>
        </div>
    </div>

    <div class="card-body p-0">
        <h5 class="card-title px-4 pt-3 pb-2 mb-0 border-bottom">Contact & Details</h5>
        <ul class="details-list">
            <li>
                <strong><i class="bi bi-telephone me-2"></i> Phone:</strong>
                {{ $recruiter->phone ?? 'Not Set' }}
            </li>
            <li>
                <strong><i class="bi bi-geo-alt me-2"></i> Location:</strong>
                {{ $recruiter->city ?? 'Not Set' }}{{ ($recruiter->city && $recruiter->country) ? ', ' : '' }}{{ $recruiter->country ?? '' }}
            </li>
            <li>
                <strong><i class="bi bi-house me-2"></i> Address:</strong>
                {{ $recruiter->address ?? 'Not Set' }}
            </li>
            <li>
                <strong><i class="bi bi-activity me-2"></i> Status:</strong>
                <span class="badge rounded-pill badge-status-{{ $recruiter->status ?? 'inactive' }}">{{ ucfirst($recruiter->status ?? 'N/A') }}</span>
            </li>
            <li>
                <strong><i class="bi bi-eye me-2"></i> Profile Visits:</strong>
                {{ number_format($recruiter->profile_visits ?? 0) }}
            </li>
            <li>
                <strong><i class="bi bi-clock-history me-2"></i> Last Login:</strong>
                {{ $recruiter->last_login_at ? \Carbon\Carbon::parse($recruiter->last_login_at)->format('d M Y, h:i A') : 'Not Set' }}
            </li>
            <li>
                <strong><i class="bi bi-linkedin me-2"></i> LinkedIn:</strong>
                @if($recruiter->linkedin_url)
                    <a href="{{ $recruiter->linkedin_url }}" target="_blank">{{ Str::limit($recruiter->linkedin_url, 40) }} <i class="bi bi-box-arrow-up-right ms-1"></i></a>
                @else
                    Not Set
                @endif
            </li>
        </ul>

        <h5 class="card-title px-4 pt-3 pb-2 mb-0 border-top border-bottom">About Recruiter</h5>
        <div class="px-4 py-3">
            <p class="mb-0 text-break">{{ $recruiter->about_me ?? 'No "About Me" information provided.' }}</p>
        </div>
    </div>
</div>



{{-- Jobs Posted by Recruiter --}}
<div class="card">
    <div class="card-header">
        Jobs Posted by {{ $recruiter->name ?? 'This Recruiter' }}
    </div>
    <div class="card-body">
        @if ($jobs->count())
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle text-nowrap">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Job Title</th>
                            <th scope="col">Location</th>
                            <th scope="col">Posted On</th>
                            <th scope="col">Status</th>
                            <th scope="col">Views</th>
                            <th scope="col">Applications</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($jobs as $index => $job)
                            <tr>
                                <td>{{ $jobs->firstItem() + $index }}</td>
                                <td>
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="text-primary text-decoration-none">
                                        {{ $job->title }}
                                    </a>
                                </td>
                                <td>{{ $job->location ?? '—' }}</td>
                                <td>{{ $job->created_at->format('d M Y') }}</td>
                                <td><span class="badge rounded-pill badge-job-status-{{ $job->status ?? 'inactive' }}">{{ ucfirst($job->status ?? 'active') }}</span></td>
                                <td>{{ number_format($job->views ?? 0) }}</td>
                                <td>{{ number_format($job->applications->count() ?? 0) }}</td>
                                <td>
                                    <a href="{{ route('admin.jobs.show', $job->id) }}" class="btn btn-sm btn-outline-primary" title="View Job Details">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $jobs->links() }}
            </div>
        @else
            <div class="alert alert-info text-center" role="alert">
                <i class="bi bi-info-circle me-2"></i> This recruiter has not posted any jobs yet.
            </div>
        @endif
    </div>
</div>
@endsection
