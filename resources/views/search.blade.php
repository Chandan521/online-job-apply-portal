@extends('layouts.app')
@section('title', 'Search Results')

@section('jobs_search_style')
    <style>
        .job-card.active-job {
            border: 2px solid #007bff;
            background-color: #f0f8ff;
        }

        /* Custom styles to match the 'drop.png' image */
        .filter-btn {
            background-color: #e8e8e8;
            /* Light grey background from image */
            border: none;
            border-radius: 0.5rem;
            /* Rounded corners */
            padding: 0.75rem 1.25rem;
            /* Ample padding */
            font-weight: 500;
            color: #333;
            /* Darker text color */
            box-shadow: none;
            /* No shadow by default to match image */
            transition: background-color 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            /* Space between text and dropdown arrow */
        }

        .filter-btn:hover {
            background-color: #d8d8d8;
            /* Slightly darker on hover */
        }

        .filter-btn::after {
            vertical-align: 0.05em;
            /* Adjust vertical alignment of caret */
        }

        /* Styles for the dropdown menu items */
        .dropdown-menu {
            border-radius: 0.75rem;
            /* More rounded corners for the menu */
            padding: 0.5rem;
            min-width: 12rem;
            /* Ensure enough width for options */
        }

        .dropdown-item {
            padding: 0.6rem 1rem;
            border-radius: 0.4rem;
            /* Slightly rounded items */
            transition: background-color 0.2s ease, color 0.2s ease;
        }

        .dropdown-item:active,
        .dropdown-item.active {
            background-color: #007bff;
            /* A standard blue for active, adjust if image has specific color */
            color: white;
        }

        .dropdown-header {
            font-size: 0.85rem;
            color: #6c757d;
            padding: 0.5rem 1rem;
        }

        /* Adjust the gap between filter buttons */
        .d-flex.gap-2 {
            gap: 0.75rem !important;
            /* Increase gap slightly for better separation */
            flex-wrap: wrap;
            /* Allow wrapping to next line if space is limited */
        }

        /* General form container styling */
        .p-3 {
            padding: 1.5rem !important;
            /* Increase padding for a more spacious look */
        }
    </style>
@endsection

@section('content')
    <div class="container my-5">
        <div class="mb-5">
            <!-- Search bar -->
            {{-- Desktop Form --}}
            <form action="{{ route('job.search') }}" method="GET" class="mb-4 d-none d-lg-block">
                <div class="input-group input-group-lg shadow-sm rounded overflow-hidden">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-search"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 border-end" placeholder="Job title or keywords"
                        name="q" value="{{ request('q') }}">
                    <span class="input-group-text bg-white border-start-0 border-end-0">|</span>
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-geo-alt"></i>
                    </span>
                    <input type="text" class="form-control border-start-0" placeholder="Location" name="form"
                        value="{{ request('form') }}">
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
                            <input type="text" class="form-control" name="q" placeholder="e.g. Laravel Developer"
                                value="{{ request('q') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Location</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-geo-alt"></i></span>
                            <input type="text" class="form-control" name="form" placeholder="e.g. Kolkata"
                                value="{{ request('form') }}">
                        </div>
                    </div>
                    <div class="d-grid">
                        <button class="btn btn-primary btn-lg" type="submit">Find Jobs</button>
                    </div>
                </div>
            </form>



        </div>



        <form method="GET" action="{{ route('job.search') }}" class="p-3 bg-white shadow rounded mb-4" style="width: auto; max-width: 50%;">
            <div class="d-flex flex-wrap gap-2 justify-content-start">
                {{-- Job Type --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-btn" type="button" id="dropdownJobType"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Job Type
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="dropdownJobType">
                        <li>
                            <h6 class="dropdown-header">Select Job Type</h6>
                        </li>
                        <li><a class="dropdown-item {{ request('type') == '' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['type' => '']) }}">All</a></li>
                        <li><a class="dropdown-item {{ request('type') == 'full-time' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['type' => 'full-time']) }}">Full-Time</a></li>
                        <li><a class="dropdown-item {{ request('type') == 'part-time' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['type' => 'part-time']) }}">Part-Time</a></li>
                    </ul>
                </div>

                {{-- Shift --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-btn" type="button" id="dropdownShift"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Shift
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="dropdownShift">
                        <li>
                            <h6 class="dropdown-header">Select Shift</h6>
                        </li>
                        <li><a class="dropdown-item {{ request('shift') == '' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['shift' => '']) }}">All</a></li>
                        <li><a class="dropdown-item {{ request('shift') == 'day' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['shift' => 'day']) }}">Day</a></li>
                        <li><a class="dropdown-item {{ request('shift') == 'night' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['shift' => 'night']) }}">Night</a></li>
                    </ul>
                </div>

                {{-- Education --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-btn" type="button" id="dropdownEducation"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Education
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="dropdownEducation">
                        <li>
                            <h6 class="dropdown-header">Select Education Level</h6>
                        </li>
                        <li><a class="dropdown-item {{ request('education') == '' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['education' => '']) }}">All</a></li>
                        <li><a class="dropdown-item {{ request('education') == 'high_school' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['education' => 'high_school']) }}">High School</a>
                        </li>
                        <li><a class="dropdown-item {{ request('education') == 'bachelor' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['education' => 'bachelor']) }}">Bachelor</a></li>
                        <li><a class="dropdown-item {{ request('education') == 'master' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['education' => 'master']) }}">Master</a></li>
                    </ul>
                </div>

                {{-- Monthly Salary (Displaying as "Pay" as per image style) --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-btn" type="button" id="dropdownMinSalary"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Pay
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="dropdownMinSalary">
                        <li>
                            <h6 class="dropdown-header">Minimum Monthly Salary</h6>
                        </li>
                        <li><a class="dropdown-item {{ request('min_salary') == '' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '']) }}">Any</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '5000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '5000']) }}">₹5,000+</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '10000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '10000']) }}">₹10,000+</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '15000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '15000']) }}">₹15,000+</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '20000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '20000']) }}">₹20,000+</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '30000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '30000']) }}">₹30,000+</a></li>
                        <li><a class="dropdown-item {{ request('min_salary') == '50000' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['min_salary' => '50000']) }}">₹50,000+</a></li>
                    </ul>
                </div>

                {{-- Date Posted --}}
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle filter-btn" type="button" id="dropdownDatePosted"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Date Posted
                    </button>
                    <ul class="dropdown-menu shadow border-0" aria-labelledby="dropdownDatePosted">
                        <li>
                            <h6 class="dropdown-header">Posted Within</h6>
                        </li>
                        <li><a class="dropdown-item {{ request('posted_within') == '' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['posted_within' => '']) }}">Anytime</a></li>
                        <li><a class="dropdown-item {{ request('posted_within') == '1' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['posted_within' => '1']) }}">Last 24 hours</a></li>
                        <li><a class="dropdown-item {{ request('posted_within') == '3' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['posted_within' => '3']) }}">Last 3 days</a></li>
                        <li><a class="dropdown-item {{ request('posted_within') == '7' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['posted_within' => '7']) }}">Last 7 days</a></li>
                        <li><a class="dropdown-item {{ request('posted_within') == '14' ? 'active' : '' }}"
                                href="{{ request()->fullUrlWithQuery(['posted_within' => '14']) }}">Last 14 days</a></li>
                    </ul>
                </div>

                {{-- Hidden fields for existing query parameters (if you also have a main search bar or other persistent filters) --}}
                @if (request('q'))
                    <input type="hidden" name="q" value="{{ request('q') }}">
                @endif
                @if (request('form'))
                    <input type="hidden" name="form" value="{{ request('form') }}">
                @endif
            </div>
        </form>


        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="row">
                    <!-- Left Column: Job Cards -->
                    <div class="col-12 col-lg-4 mb-4 mb-lg-0">
                        <div class="list-group" id="jobList">
                            @if ($displayJobs->count())
                                @foreach ($displayJobs as $j)
                                    <a href="{{ route('job.search', ['q' => request('q'), 'form' => request('form'), 'job_id' => $j->id, 'show_count' => $showCount]) }}"
                                        class="list-group-item list-group-item-action job-card {{ $activeJobId == $j->id ? 'active-job' : '' }}">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                
                                                <h6 class="mb-1">{{ $j->title }} @if(in_array($j->id, $appliedJobIds))
                        <span class="badge bg-success ms-2">Applied</span>
                    @endif</h6>
                                                <small>{{ $j->company }}</small>
                                                <div class="small text-muted">{{ $j->location }}</div>
                                                <div class="small text-muted">
                                                    @php
                                                        $type_display = '';
                                                        if (is_string($j->type)) {
                                                            $decoded_type = json_decode($j->type, true);
                                                            if (is_array($decoded_type)) {
                                                                $type_display = implode(', ', $decoded_type);
                                                            } else {
                                                                $type_display = $j->type;
                                                            }
                                                        } elseif (is_array($j->type)) {
                                                            $type_display = implode(', ', $j->type);
                                                        }

                                                        $shift_display = '';
                                                        if (is_string($j->shift)) {
                                                            $decoded_shift = json_decode($j->shift, true);
                                                            if (is_array($decoded_shift)) {
                                                                $shift_display = implode(', ', $decoded_shift);
                                                            } else {
                                                                $shift_display = $j->shift;
                                                            }
                                                        } elseif (is_array($j->shift)) {
                                                            $shift_display = implode(', ', $j->shift);
                                                        }
                                                    @endphp
                                                    {{ $type_display }} · {{ $shift_display }}
                                                </div>
                                            </div>
                                            <div>
                                                <i class="bi bi-bookmark"></i>
                                            </div>
                                        </div>
                                        <div class="mt-2 text-primary small">>> Easy Apply</div>
                                    </a>
                                @endforeach
                            @else
                                <div class="list-group-item text-center text-muted py-5">
                                    <i class="bi bi-briefcase fs-1 mb-2"></i>
                                    <div>No jobs found.</div>
                                </div>
                            @endif
                        </div>

                        @if ($canShowMore && $displayJobs->count())
                            <div class="text-center mt-3">
                                <a href="{{ route('job.search', ['q' => request('q'), 'form' => request('form'), 'show_count' => $showCount + $showMoreStep, 'job_id' => $activeJobId]) }}"
                                    class="btn btn-outline-primary btn-sm">Show More Jobs</a>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column: Job Details -->
                    <div class="col-12 col-lg-8">
                        <div class="border rounded shadow-sm p-4" id="jobDetail">
                            @if ($activeJob)
                                @include('partials.job-detail', ['job' => $activeJob])
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="bi bi-briefcase fs-1 mb-2"></i>
                                    <div>No job selected. Please choose a job to see details.</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('job_search_scripts')
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection
