@extends('layouts.app')

@section('title', $job->title . ' at ' . ($job->company ?? ($job->company_name ?? 'Unknown Company')))

@section('content')
    <div class="container-fluid py-4" style="background-color: #f8f9fa;">
        <div class="row gx-4">
            {{-- Main Job Detail --}}
            <div class="col-lg-8">
                @include('partials.job-detail', [
                    'job' => $job,
                    'skills' => $skills,
                    'initialSkills' => $initialSkills,
                    'remainingSkills' => $remainingSkills,
                ])
            </div>

            {{-- Related Jobs Sidebar --}}
            <div class="col-lg-4">
                <div class="related-jobs-container">
                    <h5 class="related-jobs-title">Related Jobs</h5>
                    <div class="related-jobs-list">
                        @forelse ($relatedJobs as $related)
                            @php
                                $deadline = $related->deadline ? \Carbon\Carbon::parse($related->deadline) : null;
                                $isExpired = $deadline && $deadline->isPast();
                                $postedDaysAgo = $related->created_at
                                    ? \Carbon\Carbon::parse($related->created_at)->diffForHumans(null, true)
                                    : null;
                                $applicationsCount = \App\Models\JobApplication::where('job_id', $related->id)->count();
                                $applicationsDisplay = $applicationsCount > 25 ? '25+' : $applicationsCount;
                            @endphp
                            <div class="related-job-card">
                                <div class="d-flex align-items-start gap-3">
                                    @if (
                                        !empty($related->company_logo) &&
                                            (filter_var($related->company_logo, FILTER_VALIDATE_URL) ||
                                                \Illuminate\Support\Facades\Storage::exists($related->company_logo)))
                                        <img src="{{ filter_var($related->company_logo, FILTER_VALIDATE_URL) ? $related->company_logo : Storage::url($related->company_logo) }}"
                                            alt="{{ $related->company ?? 'Company Logo' }}" class="related-job-logo">
                                    @else
                                        <div class="related-job-logo placeholder">
                                            <i class="bi bi-building"></i>
                                        </div>
                                    @endif

                                    <div class="related-job-info flex-grow-1">
                                        <h6 class="related-job-title">{{ Str::limit($related->title, 40) }}</h6>
                                        <p class="related-job-company">{{ $related->company ?? 'Unknown Company' }}</p>
                                        <p class="related-job-location"><i class="bi bi-geo-alt"></i>
                                            {{ $related->location ?? 'N/A' }}</p>

                                        <div class="related-job-meta mb-2">
                                            @if ($postedDaysAgo)
                                                <span class="meta-item">Posted {{ $postedDaysAgo }} ago</span>
                                            @endif
                                            <span class="meta-item">{{ $applicationsDisplay }} Applied</span>
                                            @if ($deadline)
                                                <span class="meta-item {{ $isExpired ? 'text-danger' : 'text-success' }}">
                                                    {{ $deadline->format('M d') }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- ACTION BUTTONS --}}
                                        <div class="d-flex gap-2 flex-wrap">
                                            {{-- Apply Now --}}
                                            <a href="{{ route('job.apply', $related->id ) }}"
                                                class="btn btn-primary btn-sm py-1 px-2" title="Apply Now">
                                                <i class="bi bi-send"></i>
                                            </a>

                                            {{-- Save --}}
                                            <button class="btn btn-outline-secondary btn-sm save-job-btn"
                                                data-job-id="{{ $related->id }}" title="Save Job">
                                                <i class="bi bi-bookmark"></i>
                                            </button>



                                            {{-- Copy Link --}}
                                            <button class="btn btn-outline-secondary btn-sm py-1 px-2"
                                                onclick="navigator.clipboard.writeText('{{ url('/job/' . $related->id . '/view') }}'); this.innerHTML='<i class=\'bi bi-clipboard-check\'></i>'"
                                                title="Copy Link">
                                                <i class="bi bi-clipboard"></i>
                                            </button>

                                            {{-- Share --}}
                                            <a href="{{ route('job.share', $related->id) }}"
                                                class="btn btn-outline-secondary btn-sm py-1 px-2" title="Share Job">
                                                <i class="bi bi-share"></i>
                                            </a>

                                            {{-- View --}}
                                            <a href="{{ route('job.full-view', $related->id) }}"
                                                class="btn btn-outline-secondary btn-sm py-1 px-2" title="View Job">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted">No related jobs found.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
    @push('full-view-style')
        <style>
            :root {
                --primary-color: #007bff;
                --border-color: #dee2e6;
                --background-color: #f8f9fa;
                --text-color: #212529;
                --text-muted-color: #6c757d;
                --card-background: #ffffff;
                --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                --card-hover-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
            }

            body {
                background-color: var(--background-color);
            }

            /* Job Detail Container */
            .job-detail-container {
                background-color: var(--card-background);
                border-radius: 12px;
                box-shadow: var(--card-shadow);
                overflow: hidden;
                transition: box-shadow 0.3s ease;
            }

            .job-cover-image {
                height: 200px;
                background-size: cover;
                background-position: center;
            }

            .job-cover-image.placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #e9ecef;
                font-size: 2.5rem;
                color: var(--text-muted-color);
            }

            .job-detail-content {
                display: flex;
                flex-direction: column;
                height: auto;
            }

            .job-header {
                padding: 2rem;
                background-color: var(--card-background);
                border-bottom: 1px solid var(--border-color);
                z-index: 10;
            }

            .company-logo {
                width: 64px;
                height: 64px;
                object-fit: contain;
                border-radius: 8px;
                border: 1px solid var(--border-color);
                padding: 4px;
            }

            .company-logo.placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #e9ecef;
                font-size: 2rem;
                color: var(--text-muted-color);
            }

            .job-title-section {
                flex: 1;
            }

            .job-title {
                font-size: 1.75rem;
                font-weight: 700;
                margin-bottom: 0.25rem;
            }

            .job-company-location {
                display: flex;
                align-items: center;
                gap: 1rem;
                color: var(--text-muted-color);
                margin-bottom: 0.5rem;
            }

            .job-salary {
                font-size: 1.1rem;
                font-weight: 500;
            }

            .job-actions {
                display: flex;
                gap: 0.5rem;
                margin-top: 1.5rem;
            }

            .btn-apply {
                padding: 0.5rem 1.5rem;
                font-weight: 600;
            }

            .btn-icon {
                width: 40px;
                height: 40px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }

            .job-body {
                padding: 2rem;
                overflow-y: auto;
                flex: 1;
            }

            .job-section {
                margin-bottom: 2.5rem;
            }

            .section-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
                padding-bottom: 0.5rem;
                border-bottom: 2px solid var(--primary-color);
                display: inline-block;
            }

            .skills-container {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                align-items: center;
            }

            .skill-badge {
                background-color: #e9ecef;
                color: var(--text-color);
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-size: 0.875rem;
                font-weight: 500;
            }

            .skill-toggle {
                color: var(--primary-color);
                font-weight: 600;
                text-decoration: none;
            }

            .job-details-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .detail-item {
                display: flex;
                flex-direction: column;
            }

            .detail-label {
                font-size: 0.875rem;
                color: var(--text-muted-color);
                margin-bottom: 0.25rem;
            }

            .detail-value {
                font-size: 1rem;
                font-weight: 500;
            }

            .job-description h6 {
                font-weight: 700;
                margin-top: 1.5rem;
                margin-bottom: 0.5rem;
            }

            .job-footer {
                margin-top: 2rem;
                text-align: center;
            }

            .report-job-link {
                color: var(--text-muted-color);
                text-decoration: none;
                font-size: 0.875rem;
            }

            .report-job-link:hover {
                color: #dc3545;
            }

            /* Related Jobs Sidebar */
            .related-jobs-container {
                background-color: var(--card-background);
                border-radius: 12px;
                box-shadow: var(--card-shadow);
                padding: 1.5rem;
                height: 100%;
            }

            .related-jobs-title {
                font-size: 1.25rem;
                font-weight: 600;
                margin-bottom: 1.5rem;
            }

            .related-jobs-list {
                max-height: 80vh;
                overflow-y: auto;
                padding-right: 10px;
            }

            .related-job-card {
                display: block;
                padding: 1rem;
                border-radius: 8px;
                margin-bottom: 1rem;
                transition: background-color 0.2s ease, box-shadow 0.2s ease;
                text-decoration: none;
                color: inherit;
                border: 1px solid var(--border-color);
            }

            .related-job-card:hover {
                background-color: #f8f9fa;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            }

            .related-job-logo {
                width: 48px;
                height: 48px;
                object-fit: contain;
                border-radius: 6px;
            }

            .related-job-logo.placeholder {
                display: flex;
                align-items: center;
                justify-content: center;
                background-color: #e9ecef;
                font-size: 1.5rem;
                color: var(--text-muted-color);
            }

            .related-job-info {
                flex: 1;
            }

            .related-job-title {
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .related-job-company,
            .related-job-location {
                font-size: 0.875rem;
                color: var(--text-muted-color);
                margin-bottom: 0.25rem;
            }

            .related-job-meta {
                display: flex;
                flex-wrap: wrap;
                gap: 0.75rem;
                font-size: 0.8rem;
                color: var(--text-muted-color);
                margin-top: 0.5rem;
            }

            /* Custom Scrollbar */
            .related-jobs-list::-webkit-scrollbar {
                width: 6px;
            }

            .related-jobs-list::-webkit-scrollbar-track {
                background: transparent;
            }

            .related-jobs-list::-webkit-scrollbar-thumb {
                background: #ced4da;
                border-radius: 3px;
            }

            .related-jobs-list::-webkit-scrollbar-thumb:hover {
                background: #adb5bd;
            }
        </style>
    @endpush
@endsection
