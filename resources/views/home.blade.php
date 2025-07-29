@extends('layouts.app')
@section('title', 'Home')
@section('jobs_style')
    <script>
        function scrollToJobList() {
            const jobList = document.getElementById('jobList');
            if (jobList) {
                jobList.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        }

        function toggleSkills(link) {
            const extra = document.getElementById('extraSkills');
            if (extra.classList.contains('d-none')) {
                extra.classList.remove('d-none');
                extra.classList.add('d-flex');
                link.textContent = 'Show less';
            } else {
                extra.classList.add('d-none');
                extra.classList.remove('d-flex');
                link.textContent = 'Show more';
            }
        }
    </script>
@endsection
@section('content')
    <div class="container my-5">
        <!-- ✅ Job Search Section -->
        <div class="mb-5">
            <!-- Search bar -->
            {{-- Desktop Form --}}
            <form action="{{ route('job.search') }}" method="GET" class="mb-4 d-none d-lg-block">
                <div class="input-group input-group-lg shadow-sm rounded overflow-hidden">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 border-end" placeholder="Job title or keywords"
                        name="q">
                    <span class="input-group-text bg-white border-start-0 border-end-0">|</span>
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Location" name="form">
                    <button class="btn btn-primary" type="submit">Find Jobs</button>
                </div>
            </form>

            {{-- Mobile Form --}}
            <form action="{{ route('job.search') }}" method="GET" class="mb-4 d-lg-none">
                <div class="p-3 shadow-sm rounded bg-white">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Job title or keywords</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                            <input type="text" class="form-control" name="q" placeholder="e.g. Laravel Developer">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Location</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" name="form" placeholder="e.g. Kolkata">
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">Find Jobs</button>
                    </div>
                </div>
            </form>

            <!-- Employer text -->
            <div class="text-center mt-3">
                <p class="text-muted mb-1"><strong>Employers:</strong> Post a job – your next hire is here</p>
            </div>
            <!-- Section Heading -->
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <h5 class="fw-bold mb-1">Job Suggestions for You</h5>
                    <p class="text-muted small mb-0">Jobs based on your activity on
                        {{ setting('site_name' ?? 'Name Not Set') }}</p>
                </div>
            </div>
        </div>
        <!-- ✅ Main Job Listings Layout -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <!-- Left Column: Job Cards -->
                    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                        <div class="list-group" id="jobList">
                            @if ($displayJobs->count())
                                @foreach ($displayJobs as $j)
                                    <a href="{{ url('/') }}?job_id={{ $j->id }}&show_count={{ $showCount }}"
                                        class="list-group-item list-group-item-action job-card {{ $activeJobId == $j->id ? 'active-job' : '' }}">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <h6 class="mb-1">
                                                    {{ $j->title }} #{{ $j->id }}
                                                    @if (in_array($j->id, $appliedJobIds))
                                                        <span class="badge bg-success ms-2">Applied</span>
                                                    @endif
                                                </h6>
                                                <small>{{ $j->company }}</small>
                                                <div class="small text-muted">{{ $j->location }}</div>
                                                <div class="small text-muted">
    @foreach (json_decode($j->type, true) as $type)
        <span class="badge bg-primary me-1">{{ $type }}</span>
    @endforeach

    @foreach (json_decode($j->shift, true) as $shift)
        <span class="badge bg-secondary me-1">{{ $shift }}</span>
    @endforeach
</div>

                                               <div class="small text-muted">
    <span class="badge bg-primary">{{ $j->salary }}</span>
</div>

                                            </div>
                                            <div>
                                                <i class="bi bi-bookmark"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-primary small">&gt;&gt; Easy Apply</div>
                                    </a>
                                @endforeach
                            @else
                                <div class="list-group-item text-center text-muted py-5">
                                    <i class="bi bi-briefcase fs-1 mb-2"></i>
                                    <div>No jobs found.</div>
                                </div>
                            @endif
                        </div>
                        <!-- Show More Button -->
                        @if ($canShowMore && $displayJobs->count())
                            <div class="text-center mt-3">
                                <a href="{{ url('/') }}?show_count={{ $showCount + $showMoreStep }}{{ $activeJobId ? '&job_id=' . $activeJobId : '' }}"
                                    class="btn btn-outline-primary btn-sm">Show More Jobs</a>
                            </div>
                        @endif
                    </div>
                    <!-- Right Column: Job Details or Default View -->
                    <div class="col-12 col-lg-8">
                        <div class="border rounded p-4 shadow-sm" id="jobDetail">
                            @if ($activeJobId && $jobs->where('id', $activeJobId)->count())
                                <!-- Show Job Detail -->
                                <div class="d-lg-none mb-3">
                                    <button class="btn btn-link text-decoration-none d-flex align-items-center gap-1 ps-0"
                                        onclick="scrollToJobList()">
                                        <i class="bi bi-arrow-left"></i> Back to Jobs
                                    </button>
                                </div>
                                @include('partials.job-detail', [
                                    'job' => $jobs->where('id', $activeJobId)->first(),
                                ])
                            @elseif ($jobs->count() == 0)
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-briefcase fs-1 mb-2"></i>
                                    <div>No jobs available. Please check back later.</div>
                                </div>
                            @else
                                <!-- Default Job View -->
                                <div class="text-muted">
                                    @include('partials.default-view')
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Footer Section --}}
@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection
@section('scripts')
