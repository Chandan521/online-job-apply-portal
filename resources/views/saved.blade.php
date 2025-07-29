@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Saved Jobs')

{{-- Saved Page Styles --}}
@push('styles')
    <style>
        .custom-tabs .nav-link.active {
            color: #0d6efd;
            border-bottom: 3px solid #0d6efd;
            background-color: transparent;
        }

        .mini-tabs {
            display: flex;
            border-bottom: none;
            padding-bottom: 4px;
        }

        .mini-tabs .nav-item {
            margin-right: 2rem;
            text-align: center;
        }

        .mini-tabs .nav-link {
            background: none;
            border: none;
            border-radius: 0;
            padding: 0;
            color: #666;
        }

        .mini-tabs .nav-link .count {
            font-weight: 600;
            font-size: 14px;
            line-height: 1.2;
        }

        .mini-tabs .nav-link .label {
            font-size: 14px;
            color: #666;
        }

        .mini-tabs .nav-link.active {
            border-bottom: 3px solid #000;
        }

        .mini-tabs .nav-link.active .label {
            font-weight: bold;
            color: #000;
        }

        .hover-bg-light:hover {
            background-color: #f8f9fa;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .job-card:hover {
            background-color: #f1f1f1;
        }
    </style>
@endpush

{{-- Main Content --}}
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <h4 class="fw-bold mb-4">My Jobs</h4>

                {{-- Tabs --}}
                <ul class="nav mini-tabs" id="jobTabs" role="tablist">
                    @php
                        $tabs = [
                            'saved' => ['label' => 'Saved', 'count' => $savedJobs->count()],
                            'applied' => [
                                'label' => 'Applied',
                                'count' => $appliedJobs->count() > 99 ? '99+' : $appliedJobs->count(),
                            ],
                            // 'interviews' => ['label' => 'Interviews', 'count' => $interviewJobs->count()],
                            // 'archived' => ['label' => 'Archived', 'count' => 0],
                        ];
                    @endphp
                    @foreach ($tabs as $key => $tab)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" id="{{ $key }}-tab"
                                data-bs-toggle="tab" data-bs-target="#{{ $key }}" type="button" role="tab"
                                aria-controls="{{ $key }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                <div class="count">{{ $tab['count'] }}</div>
                                <div class="label">{{ $tab['label'] }}</div>
                            </button>
                        </li>
                    @endforeach
                </ul>

                <hr class="tab-underline">

                {{-- Tab Content --}}
                <div class="tab-content" id="jobTabsContent">
                    {{-- Saved Tab --}}
                    <div class="tab-pane fade show active" id="saved" role="tabpanel" aria-labelledby="saved-tab">
                        @if ($savedJobs->isEmpty())
                            <div class="text-center">
                                <h5>No jobs saved yet</h5>
                                <p class="text-muted">Jobs you save appear here.</p>
                                <p class="text-primary">Having an issue with My Jobs? <a href="#">Tell us more</a></p>
                                <button class="btn btn-primary">Find Jobs</button>
                            </div>
                        @else
                            @foreach ($savedJobs as $job)
                                <div class="card mb-3 shadow-sm job-card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <h5 class="mb-1">{{ $job->title }}</h5>
                                            <div class="text-muted small">
                                                {{ $job->company }} &mdash; {{ $job->location }}
                                            </div>

                                            @if ($job->deadline && \Carbon\Carbon::parse($job->deadline)->isPast())
                                                <span class="badge bg-danger mt-1">This job is expired</span>
                                            @endif
                                        </div>

                                        <div class="text-end d-flex align-items-center">
                                            <a href="{{ route('job.full', $job->id) }}"
                                                class="btn btn-outline-primary btn-sm me-2">
                                                View Job
                                            </a>
                                            <button onclick="unsaveJob({{ $job->id }})"
                                                class="btn btn-outline-danger btn-sm" title="Remove from saved">
                                                <i class="bi bi-bookmark-x"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        @endif
                    </div>

                    {{-- Applied Tab --}}
                    <div class="tab-pane fade" id="applied" role="tabpanel" aria-labelledby="applied-tab">
                        @if ($appliedJobs->isEmpty())
                            <div class="text-center">
                                <h5>No jobs applied yet</h5>
                                <p class="text-muted">Jobs you apply to appear here.</p>
                                <a href="{{ route('home') }}" class="btn btn-primary mt-3">Apply for a Job</a>
                            </div>
                        @else
                            @foreach ($appliedJobs as $job)
                                <div class="card mb-3 shadow-sm job-card">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div>
                                            <h5 class="mb-1">{{ $job->title }}</h5>
                                            <div class="text-muted small">
                                                {{ $job->company }} &mdash; {{ $job->location }}
                                            </div>
                                            <div class="small text-secondary">Applied on: {{ $job->applied_on }}</div>

                                            @if ($job->deadline && \Carbon\Carbon::parse($job->deadline)->isPast())
                                                <span class="badge bg-danger mt-1">This job is expired</span>
                                            @endif
                                        </div>

                                        @if ($job->id)
                                            <div class="text-end">
                                                <span
                                                    class="badge {{ $job->status['class'] }}">{{ $job->status['label'] }}</span>
                                                <a href="{{ route('job.full', $job->id) }}"
                                                    class="btn btn-outline-primary btn-sm ms-2">
                                                    View Job
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                        @endif
                    </div>


                    {{-- Archived Tab --}}
                    {{-- <div class="tab-pane fade" id="archived" role="tabpanel" aria-labelledby="archived-tab">
                        <div class="text-center">
                            <h5>No archived jobs</h5>
                            <p class="text-muted">Archived jobs will appear here.</p>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Footer --}}
@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong>{{ setting('site_name') ?? 'Name Not Set' }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection

@push('saved_scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // If we are on /saved without query param, redirect with localStorage IDs
            const url = new URL(window.location.href);
            const params = new URLSearchParams(url.search);
            if (!params.has('ids')) {
                const saved = JSON.parse(localStorage.getItem('saved_jobs') || '[]');
                if (saved.length > 0) {
                    const newUrl = `${window.location.pathname}?ids=${saved.join(',')}`;
                    window.location.replace(newUrl);
                }
            }
        });

        function unsaveJob(jobId) {
            let savedJobs = JSON.parse(localStorage.getItem('saved_jobs') || '[]');

            // Ensure types match
            savedJobs = savedJobs.filter(id => parseInt(id) !== parseInt(jobId));

            localStorage.setItem('saved_jobs', JSON.stringify(savedJobs));

            // Refresh page with updated query
            const url = new URL(window.location.href);
            if (savedJobs.length > 0) {
                url.searchParams.set('ids', savedJobs.join(','));
            } else {
                url.searchParams.delete('ids');
            }

            window.location.href = url.toString();
        }
    </script>
@endpush
