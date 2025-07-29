@extends('recruiter.layout.dashboard_layout')
@section('title', 'Dashboard')
{{-- Recruiter Dashboard --}}
@section('content')
    <style>
        .chart-container {
            height: 100%;
        }
    </style>


    <div class="dashboard-header">
        <h2 class="fw-bold">Recruiter Dashboard</h2>
        <p class="mb-0" style="color: var(--text-secondary);">
            Welcome back, {{ ucwords($user->name) }}. Here's what's happening with your job postings today.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <i class="bi bi-briefcase"></i>
                <h5>Active Jobs</h5>
                <h3 class="fw-bold">{{ $jobsCount }}</h3>
                <p class="text-success small"><i class="bi bi-arrow-up"></i> 2 new this week</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <i class="bi bi-people"></i>
                <h5>Applications</h5>
                <h3 class="fw-bold">{{ $applicantsCount }}</h3>
                <p class="text-success small"><i class="bi bi-arrow-up"></i> 12 today</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <i class="bi bi-calendar-check"></i>
                <h5>Interviews</h5>
                <h3 class="fw-bold">{{ $interviewCount }}</h3>
                <p class="text-warning small"><i class="bi bi-exclamation-circle"></i> 2 scheduled today</p>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="stats-card">
                <i class="bi bi-star"></i>
                <h5>Top Candidates</h5>
                <h3 class="fw-bold">{{ $topCandidatesCount }}</h3>
                <p class="text-info small">Ready for final review</p>
            </div>
        </div>
    </div>

    <!-- Charts and Activity Section -->
    <div class="row mb-4">
        <div class="col-lg-8 mb-4">
            <!-- Chart Section (unchanged placeholder) -->
            <div class="chart-container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Application Trends</h5>
                    <div>
                        <button class="btn btn-sm btn-outline-secondary me-1" onclick="loadChart('week')">Week</button>
                        <button class="btn btn-sm btn-outline-primary me-1" onclick="loadChart('month')">Month</button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="loadChart('quarter')">Quarter</button>
                    </div>
                </div>

                <canvas id="applicationChart" height="120"></canvas>
            </div>

        </div>

        <!-- Dynamic Activity Feed -->
        <div class="col-lg-4 mb-4">
            <div class="activity-card">
                <h5 class="mb-4">Recent Activity</h5>

                @foreach ($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi {{ $activity['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <h6>{{ $activity['title'] }}</h6>
                            <p>{{ $activity['description'] }}</p>
                            <small>{{ $activity['time'] }}</small>
                        </div>
                    </div>
                @endforeach

                <div class="mt-3 text-center">
                    <a href="#" class="text-decoration-none">View All Activity</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Job Postings -->
        <div class="col-lg-8 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5>Active Job Postings</h5>
                <button class="btn btn-sm btn-primary"><i class="bi bi-plus"></i> New Job</button>
            </div>

            <div class="job-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Senior Frontend Developer</h6>
                        <p class="mb-1 text-muted small">React, TypeScript, Redux</p>
                        <span class="job-status status-active">Active</span>
                    </div>
                    <div class="text-end">
                        <div class="mb-1">24 Applications</div>
                        <div class="progress" style="width: 150px;">
                            <div class="progress-bar" style="width: 65%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="job-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">UX/UI Designer</h6>
                        <p class="mb-1 text-muted small">Figma, Adobe XD, User Research</p>
                        <span class="job-status status-active">Active</span>
                    </div>
                    <div class="text-end">
                        <div class="mb-1">18 Applications</div>
                        <div class="progress" style="width: 150px;">
                            <div class="progress-bar" style="width: 45%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="job-card">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Backend Engineer (Node.js)</h6>
                        <p class="mb-1 text-muted small">Node.js, Express, MongoDB</p>
                        <span class="job-status status-pending">Pending Review</span>
                    </div>
                    <div class="text-end">
                        <div class="mb-1">8 Applications</div>
                        <div class="progress" style="width: 150px;">
                            <div class="progress-bar" style="width: 25%;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none">View All Jobs</a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <h5 class="mb-4">Quick Actions</h5>

            <div class="row">
                @if (hasAccess('create_job'))
                <div class="col-md-6 col-sm-6 mb-3">
                    <a href="{{ route('create_job_view') }}" class="text-decoration-none">
                        <div class="quick-action">
                            <i class="bi bi-briefcase"></i>
                            <p>Post New Job</p>
                        </div>
                    </a>
                </div>
                @endif
                @if (hasAccess('manage_application'))
                <div class="col-md-6 col-sm-6 mb-3">
                    <a href="{{ route('recruiter.interview.index') }}" class="text-decoration-none">
                        <div class="quick-action">
                            <i class="bi bi-calendar-plus"></i>
                            <p>Schedule Interview</p>
                        </div>
                    </a>
                </div>
                @endif
                @if (hasAccess('manage_application'))
                <div class="col-md-6 col-sm-6 mb-3">
                    <a href="{{ route('job_application.index') }}" class="text-decoration-none">
                        <div class="quick-action">
                            <i class="bi bi-file-earmark-text"></i>
                            <p>Generate Report</p>
                        </div>
                    </a>
                </div>
                @endif
                <div class="col-md-6 col-sm-6 mb-3">
                    <a href="{{ route('recruiter.account.settings') }}" class="text-decoration-none">
                        <div class="quick-action">
                            <i class="bi bi-gear"></i>
                            <p>Settings</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('applicationChart').getContext('2d');

        let chart;

        function loadChart(period = 'week') {
            const labels = {
                week: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                month: Array.from({
                    length: 30
                }, (_, i) => `Day ${i + 1}`),
                quarter: ['Jan', 'Feb', 'Mar'],
            } [period];

            const data = {
                week: [12, 19, 3, 5, 2, 3, 7],
                month: Array.from({
                    length: 30
                }, () => Math.floor(Math.random() * 15)),
                quarter: [120, 90, 150],
            } [period];

            if (chart) chart.destroy();

            chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Applications',
                        data: data,
                        fill: false,
                        borderColor: 'rgb(13, 110, 253)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Initial load
        loadChart('week');
    </script>

@endsection

@section('footer')
    <footer class="mt-auto w-100 bg-light text-center py-3 text-muted small"
        style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 100; border-top: 1px solid #ddd; transition: left 0.3s;">
        © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">
            | Empowering recruiters to find top talent with ease.
        </span>
    </footer>
@endsection
