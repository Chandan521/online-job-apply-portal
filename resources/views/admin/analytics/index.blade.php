@extends('admin.layout.app')

@section('title', 'Admin - Analytics')

@section('page-title', 'Analytics')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Analytics</h1>
    </div>

    <div class="row g-4">
        {{-- Quick Insights Panel --}}
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Users</h5>
                    <h2>{{ $totaljob_seeker + $totalrecruiter }}</h2>
                    <small>Recruiters: {{ $totalrecruiter }} | Job Seekers: {{ $totaljob_seeker }} </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Total Jobs</h5>
                    <h2>{{ $total_jobs }}</h2>
                    <small>Today: {{ $todayJobs }} | This Week: {{ $thisWeekJobs }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5>Total Applications</h5>
                    <h2>{{ $totalApplications }}</h2>
                    <small>Today: {{ $todayApplications }} | Avg/Job: {{ $avgApplicationsPerJob }}</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-dark">
                <div class="card-body">
                    <h5>Monthly Revenue</h5>
                    <h2>₹32,000</h2>
                    <small>Completed: ₹28,000 | Pending: ₹4,000</small>
                </div>
            </div>
        </div>
    </div>

    {{-- User Analytics --}}
    <div class="card mt-4">
        <div class="card-header bg-light text-dark fw-bold">
            <h4 class="mb-0">User Analytics</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-5">
                <canvas id="userRolePieChart" height="200"></canvas>
            </div>
            <div class="col-md-7">
                <canvas id="registrationTrendChart" height="200"></canvas>
            </div>

            <div class="col-md-12 mt-4">
                <h6>Top Active Job Seekers</h6>
                <ul>
                    @forelse ($topActiveJobSeekers as $seeker)
                        <li>{{ $seeker->name }} — {{ $seeker->applications_count }} applications</li>
                    @empty
                        <li>No job seekers found.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>

    {{-- Job Analytics --}}
    <div class="card mt-4">
        <div class="card-header bg-light text-dark fw-bold">
            <h4 class="mb-0">Job Analytics</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-5">
                <canvas id="jobsByCategoryChart" height="150"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="jobsByStatusChart" height="250"></canvas>
            </div>

            <div class="col-md-12 mt-3" style="font-size: 0.9rem;">
                <h6>Top Recruiters</h6>
                <ul class="pl-3">
                    @forelse ($topRecruiters as $recruiter)
                        <li>{{ $recruiter->recruiter_name }} — {{ $recruiter->job_count }} jobs</li>
                    @empty
                        <li>No recruiters found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>


    {{-- Application Analytics --}}
    <div class="card mt-4">
        <div class="card-header bg-light text-dark fw-bold">
            <h4 class="mb-0">Application Analytics</h4>
        </div>
        <div class="card-body row">
            <div class="col-md-6">
                <canvas id="applicationStatusChart" height="150"></canvas>
            </div>
            <div class="col-md-6">
                <h6>Top Applied Jobs</h6>
                <ul class="pl-3" style="font-size: 0.9rem;">
                    @forelse ($topAppliedJobs as $job)
                        <li>{{ $job->title }} – {{ $job->application_count }} applications</li>
                    @empty
                        <li>No applications found.</li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>


    {{-- Traffic & Engagement --}}
    <div class="card mt-4">
        <div class="card-header bg-light text-dark fw-bold">
            <h4 class="mb-0">Traffic & Engagement</h4>
        </div>
        <div class="card-body">
            <p><strong>Top Search Keywords:</strong>
                {{ $topKeywords->implode(', ') ?: 'No data available' }}
            </p>

            <p><strong>Most Viewed Jobs:</strong></p>
            <ul class="pl-3" style="font-size: 0.9rem;">
                @forelse ($mostViewedJobs as $job)
                    <li>{{ $job->title }} — {{ number_format($job->views) }} views</li>
                @empty
                    <li>No viewed jobs found.</li>
                @endforelse
            </ul>

            <p><strong>Most Visited Recruiters:</strong></p>
            <ul class="pl-3" style="font-size: 0.9rem;">
                @forelse ($mostVisitedRecruiters as $recruiter)
                    <li>{{ $recruiter->name }} — {{ number_format($recruiter->profile_visits) }} visits</li>
                @empty
                    <li>No recruiter visit data.</li>
                @endforelse
            </ul>

            <p><strong>Avg Time on Site:</strong> {{ $avgTimeInMinutes }} mins</p>
        </div>
    </div>


    {{-- System Health --}}
    <div class="card mt-4">
        <div class="card-header bg-light text-dark fw-bold">
            <h4 class="mb-0">System Health</h4>
        </div>
        <div class="card-body">
            <p><strong>Failed Jobs:</strong> {{ $failedJobsCount }}</p>
            <p><strong>Email Failures:</strong> {{ $emailFailures }}</p>
            <p><strong>Login Device Types:</strong></p>
            <ul class="pl-3" style="font-size: 0.9rem;">
                <li>Desktop: {{ $devicePercents['Desktop'] }}%</li>
                <li>Mobile: {{ $devicePercents['Mobile'] }}%</li>
                <li>Tablet: {{ $devicePercents['Tablet'] }}%</li>
            </ul>
        </div>
    </div>


    {{-- Export and Filters --}}
    <div class="card mt-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('admin.analytics') }}">
            <input type="date" name="start" class="form-control d-inline-block w-auto" value="{{ request('start') }}">
            <input type="date" name="end" class="form-control d-inline-block w-auto" value="{{ request('end') }}">
            <button type="submit" class="btn btn-info">Filter</button>
        </form>

        <a href="{{ route('admin.analytics.export', request()->only(['start', 'end'])) }}" class="btn btn-outline-primary">
            Export CSV
        </a>
    </div>
</div>

@endsection
@push('analytics-scripts')
    <script>
        const rolePieCtx = document.getElementById('userRolePieChart').getContext('2d');
        new Chart(rolePieCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($roleCounts->keys()) !!},
                datasets: [{
                    data: {!! json_encode($roleCounts->values()) !!},
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
                }]
            }
        });

        const regTrendCtx = document.getElementById('registrationTrendChart').getContext('2d');
        new Chart(regTrendCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($registrationTrend->keys()) !!},
                datasets: [{
                    label: 'Registrations',
                    data: {!! json_encode($registrationTrend->values()) !!},
                    borderColor: '#36A2EB',
                    fill: false,
                    tension: 0.4
                }]
            }
        });
        // 📊 Jobs by Category
        new Chart(document.getElementById('jobsByCategoryChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($jobsByCategory->keys()) !!},
                datasets: [{
                    data: {!! json_encode($jobsByCategory->values()) !!},
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56', '#4BC0C0', '#9966FF'],
                }]
            }
        });

        // 📊 Jobs by Status
        new Chart(document.getElementById('jobsByStatusChart'), {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_keys($jobsByStatus)) !!},
                datasets: [{
                    label: 'Job Status',
                    data: {!! json_encode(array_values($jobsByStatus)) !!},
                    backgroundColor: ['#4CAF50', '#F44336']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
        // 📊 Application Status
        // ✅ Application Status Chart
        const appStatusCtx = document.getElementById('applicationStatusChart').getContext('2d');
        new Chart(appStatusCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($applicationStatusCounts->keys()) !!},
                datasets: [{
                    label: 'Applications',
                    data: {!! json_encode($applicationStatusCounts->values()) !!},
                    backgroundColor: ['#4CAF50', '#FF9800', '#F44336', '#2196F3']
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        precision: 0
                    }
                }
            }
        });
    </script>
@endpush
