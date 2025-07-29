@extends('admin.layout.app')

@section('title', 'Job Details')
@section('page-title', 'Job Details')

@push('view_job_styles')
<style>
    /* General Admin Styling Adjustments */
    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        background-color: var(--bs-dark); /* Lighter header for a cleaner look */
        border-bottom: 1px solid var(--bs-border-color);
        font-weight: 600;
        color: var(--bs-light);
    }

    .list-group-item {
        border-color: var(--bs-border-color-translucent);
        background-color: var(--bs-body-bg);
    }

    .list-group-item strong {
        min-width: 180px; /* Slightly wider for better alignment */
        display: inline-block;
        color: var(--bs-primary);
        font-weight: 600;
    }

    /* Image Previews */
    .img-preview {
        max-height: 180px;
        object-fit: contain;
        border: 1px solid var(--bs-border-color);
        padding: 0.5rem;
        background-color: var(--bs-white);
        border-radius: 0.5rem;
        transition: transform 0.2s ease-in-out;
    }

    .img-preview:hover {
        transform: scale(1.02);
    }

    /* Status Badges */
    .badge-status-submitted { background-color: #6c757d; } /* Gray */
    .badge-status-under_review { background-color: #ffc107; color: #343a40; } /* Warning - Yellow */
    .badge-status-shortlisted { background-color: #0dcaf0; } /* Info - Light Blue */
    .badge-status-interview { background-color: #0d6efd; } /* Primary - Blue */
    .badge-status-selected { background-color: #198754; } /* Success - Green */
    .badge-status-rejected { background-color: #dc3545; } /* Danger - Red */
    .badge-status-hired { background-color: #20c997; } /* Teal */
    .badge-status-withdrawn { background-color: #6f42c1; } /* Purple */

    /* Table Styling */
    .table-dark th,
    .table-dark td {
        border-color: var(--bs-gray-700);
    }
    .table-dark {
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .table-dark thead th {
        background-color: var(--bs-dark);
        color: var(--bs-white);
    }
    .table-responsive {
        border-radius: 0.5rem; /* Apply border-radius to the responsive wrapper for consistent corners */
    }

    .btn-outline-primary {
        --bs-btn-color: var(--bs-primary);
        --bs-btn-border-color: var(--bs-primary);
        --bs-btn-hover-bg: var(--bs-primary);
        --bs-btn-hover-color: var(--bs-white);
        --bs-btn-active-bg: var(--bs-primary);
        --bs-btn-active-border-color: var(--bs-primary);
    }

    /* Accordion Customization */
    .accordion-button:not(.collapsed) {
        color: var(--bs-primary);
        background-color: var(--bs-blue-100);
        box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
    }
    .accordion-button {
        font-weight: 600;
        color: var(--bs-dark);
    }
    .accordion-body {
        padding: 1rem 1.25rem;
        background-color: var(--bs-light);
        border-radius: 0 0 0.375rem 0.375rem;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h1 class="h2 mb-0 text-primary">{{ $job->title ?? 'Job Details' }}</h1>

    @if ($job->is_approved)
        {{-- ✅ Show only View button if approved --}}
        <a href="{{ route('job.full-view', $job->id) }}" target="_blank" class="btn btn-outline-primary btn-lg">
            <i class="bi bi-box-arrow-up-right me-2"></i> View Public Job Post
        </a>
    @else
        {{-- ❌ Show only Approve/Reject buttons if not approved --}}
        <div class="d-flex gap-2 align-items-center">
            <form action="{{ route('admin.jobs.approve', $job->id) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-circle me-1"></i> Approve
                </button>
            </form>

            <form action="{{ route('admin.jobs.reject', $job->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this job?');">
                @csrf
                <button type="submit" class="btn btn-danger btn-lg">
                    <i class="bi bi-x-circle me-1"></i> Reject
                </button>
            </form>
        </div>
    @endif
</div>




<div class="row">
    {{-- Job Information --}}
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                Job Overview
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Company:</strong> {{ $job->company ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Location:</strong> {{ $job->location ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Type:</strong> {{ is_array($job->type) ? implode(', ', $job->type) : $job->type ?? '—' }}</li>
                    <li class="list-group-item"><strong>Shift:</strong> {{ is_array($job->shift) ? implode(', ', $job->shift) : $job->shift ?? '—' }}</li>
                    <li class="list-group-item"><strong>Salary:</strong> ₹{{ number_format($job->salary_from ?? 0) }} - ₹{{ number_format($job->salary_to ?? 0) }} per annum</li>
                    <li class="list-group-item"><strong>Deadline:</strong> {{ $job->deadline ? \Carbon\Carbon::parse($job->deadline)->format('d M Y') : '—' }}</li>
                    <li class="list-group-item"><strong>Experience:</strong> {{ $job->experience ?? '—' }}</li>
                    <li class="list-group-item"><strong>Employment Level:</strong> {{ is_array($job->employment_level) ? implode(', ', $job->employment_level) : $job->employment_level ?? '—' }}</li>
                    <li class="list-group-item"><strong>Industry:</strong> {{ $job->industry ?? '—' }}</li>
                    <li class="list-group-item"><strong>Remote Work:</strong> <span class="badge bg-{{ $job->is_remote ? 'success' : 'secondary' }}">{{ $job->is_remote ? 'Yes' : 'No' }}</span></li>
                    <li class="list-group-item"><strong>Required Skills:</strong> {{ $job->skills ?? '—' }}</li>
                    <li class="list-group-item">
                        <strong>Application Link:</strong>
                        @if($job->application_url)
                            <a href="{{ $job->application_url }}" target="_blank" class="text-decoration-none">{{ Str::limit($job->application_url, 50) }} <i class="bi bi-link-45deg ms-1"></i></a>
                        @else
                            —
                        @endif
                    </li>
                    <li class="list-group-item"><strong>Job Status:</strong> <span class="badge bg-success">{{ ucfirst($job->status ?? 'active') }}</span></li>
                    <li class="list-group-item"><strong>Total Views:</strong> {{ number_format($job->views ?? 0) }}</li>
                    <li class="list-group-item"><strong>Posted On:</strong> {{ $job->created_at ? $job->created_at->format('d M Y, h:i A') : 'N/A' }}</li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Company Assets & Recruiter Info --}}
    <div class="col-lg-4 mb-4">
        <div class="card mb-4">
            <div class="card-header">
                Company Visuals
            </div>
            <div class="card-body text-center">
                <div class="mb-4">
                    <p class="mb-2 text-muted fw-bold">Company Logo:</p>
                    @if($job->company_logo)
                        <img src="{{ asset('/' . $job->company_logo) }}" class="img-preview img-fluid" alt="Company Logo">
                    @else
                        <img src="{{ asset('assets/company_logos/logo1.png') }}" class="img-preview img-fluid rounded-circle" alt="Default Logo">
                    @endif
                </div>
                <div>
                    <p class="mb-2 text-muted fw-bold">Cover Image:</p>
                    @if($job->cover_image)
                        <img src="{{ asset('storage/' . $job->cover_image) }}" class="img-preview w-100" alt="Cover Image" style="max-height: 200px;">
                    @else
                        <img src="{{ asset('assets/cover_images/cover1.jpg') }}" class="img-preview w-100" alt="Default Cover">
                    @endif
                </div>
            </div>
        </div>

        @if($job->recruiter)
        <div class="card">
            <div class="card-header">
                Recruiter Information
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Name:</strong> {{ $job->recruiter->name ?? 'N/A' }}</li>
                    <li class="list-group-item"><strong>Email:</strong> <a href="mailto:{{ $job->recruiter->email }}" class="text-decoration-none">{{ $job->recruiter->email ?? 'N/A' }}</a></li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>



{{-- Detailed Job Descriptions (Accordion) --}}
<div class="card mb-4">
    <div class="card-header">
        Detailed Job Descriptions
    </div>
    <div class="card-body p-0"> {{-- Remove padding here as accordion-body will have it --}}
        <div class="accordion accordion-flush" id="jobDescriptionsAccordion">
            {{-- Job Description --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingDescription">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescription" aria-expanded="false" aria-controls="collapseDescription">
                        Job Description
                    </button>
                </h2>
                <div id="collapseDescription" class="accordion-collapse collapse" aria-labelledby="headingDescription" data-bs-parent="#jobDescriptionsAccordion">
                    <div class="accordion-body">
                        {!! $job->description ? nl2br(e($job->description)) : '<p class="text-muted mb-0">No detailed description provided.</p>' !!}
                    </div>
                </div>
            </div>

            {{-- Key Requirements --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRequirements">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRequirements" aria-expanded="false" aria-controls="collapseRequirements">
                        Key Requirements
                    </button>
                </h2>
                <div id="collapseRequirements" class="accordion-collapse collapse" aria-labelledby="headingRequirements" data-bs-parent="#jobDescriptionsAccordion">
                    <div class="accordion-body">
                        {!! $job->requirements ? nl2br(e($job->requirements)) : '<p class="text-muted mb-0">No specific requirements listed.</p>' !!}
                    </div>
                </div>
            </div>

            {{-- Core Responsibilities --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingResponsibilities">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseResponsibilities" aria-expanded="false" aria-controls="collapseResponsibilities">
                        Core Responsibilities
                    </button>
                </h2>
                <div id="collapseResponsibilities" class="accordion-collapse collapse" aria-labelledby="headingResponsibilities" data-bs-parent="#jobDescriptionsAccordion">
                    <div class="accordion-body">
                        {!! $job->responsibilities ? nl2br(e($job->responsibilities)) : '<p class="text-muted mb-0">No responsibilities detailed.</p>' !!}
                    </div>
                </div>
            </div>

            {{-- Benefits Offered --}}
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingBenefits">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseBenefits" aria-expanded="false" aria-controls="collapseBenefits">
                        Benefits Offered
                    </button>
                </h2>
                <div id="collapseBenefits" class="accordion-collapse collapse" aria-labelledby="headingBenefits" data-bs-parent="#jobDescriptionsAccordion">
                    <div class="accordion-body">
                        {!! $job->benefits ? nl2br(e($job->benefits)) : '<p class="text-muted mb-0">No benefits information provided.</p>' !!}
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
</div>



{{-- Applications --}}
<div class="card">
    <div class="card-header">
        Applications ({{ $job->applications->count() }})
    </div>
    <div class="card-body">
        @if($job->applications->count())
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle text-nowrap">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Candidate Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Application Status</th>
                        <th scope="col">Applied On</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($job->applications as $index => $app)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $app->user->name ?? 'N/A' }}</td>
                            <td><a href="mailto:{{ $app->user->email }}" class="text-primary text-decoration-none">{{ $app->user->email ?? 'N/A' }}</a></td>
                            <td>
                                <span class="badge rounded-pill text-uppercase badge-status-{{ $app->status }}">
                                    {{ ucfirst(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </td>
                            <td>{{ $app->created_at ? $app->created_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="alert alert-info text-center" role="alert">
            <i class="bi bi-info-circle me-2"></i> No applications have been submitted for this job yet.
        </div>
        @endif
    </div>
    <a href="{{ url()->previous() }}" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Back
        </a>
</div>
@endsection