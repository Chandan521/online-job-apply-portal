@extends('admin.layout.app')

@section('title', 'Admin - Dashboard')

@section('content')
    <div class="page-header">
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Welcome back! Here's what's happening with your job portal today.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon bg-primary">
                    <i class="bi bi-briefcase"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $activeJobs }}</h3>
                    <p class="stats-label">Active Jobs</p>
                    <span class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> +12%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon bg-success">
                    <i class="bi bi-file-earmark-person"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $job_application }}</h3>
                    <p class="stats-label">Applications</p>
                    <span class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> +8%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon bg-warning">
                    <i class="bi bi-people"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $activeCandidate }}</h3>
                    <p class="stats-label">Candidates</p>
                    <span class="stats-change positive">
                        <i class="bi bi-arrow-up"></i> +15%
                    </span>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="stats-card">
                <div class="stats-icon bg-info">
                    <i class="bi bi-building"></i>
                </div>
                <div class="stats-content">
                    <h3 class="stats-number">{{ $activeCompany }}</h3>
                    <p class="stats-label">Companies</p>
                    <span class="stats-change negative">
                        <i class="bi bi-arrow-down"></i> -3%
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Tables Row -->
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">{{ setting('site_name' ?? 'Name Not Set') }} Site Traffic</h5>
                    <div class="card-actions">
                        <select id="trafficRange" class="form-select form-select-sm">
                            <option value="7">Last 7 days</option>
                            <option value="30">Last 30 days</option>
                            <option value="90">Last 3 months</option>
                        </select>

                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-placeholder">
                        {{-- hi
                    <i class="bi bi-bar-chart-line"></i>
                    <p>Chart visualization would go here</p> --}}
                        <canvas id="siteTrafficChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Recent Applications</h5>
                </div>

                <div class="card-body p-0">
                    @forelse($recentApplications as $app)
                        <div class="application-item">
                            <div class="application-avatar">
                                <img src="{{ $app->user && $app->user->profile_photo
                                    ? asset('storage/' . $app->user->profile_photo)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($app->first_name . ' ' . $app->last_name) . '&background=random' }}"
                                    alt="{{ $app->first_name }}" class="rounded-circle" width="40" height="40"
                                    style="object-fit: cover;">
                            </div>
                            <div class="application-info">
                                <h6>{{ $app->first_name }} {{ $app->last_name }}</h6>
                                <p>{{ $app->job->title ?? 'N/A' }}</p>
                                <small>{{ $app->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="application-status">
                                {{-- Modified Status Badge --}}
                                @php
                                    $statusClass = '';
                                    switch ($app->status) {
                                        case 'submitted':
                                            $statusClass = 'bg-secondary'; // Gray
                                            break;
                                        case 'under_review':
                                            $statusClass = 'bg-warning text-dark'; // Yellow
                                            break;
                                        case 'shortlisted':
                                            $statusClass = 'bg-info'; // Light Blue
                                            break;
                                        case 'interview':
                                            $statusClass = 'bg-primary'; // Blue
                                            break;
                                        case 'selected':
                                            $statusClass = 'bg-success'; // Green
                                            break;
                                        case 'rejected':
                                            $statusClass = 'bg-danger'; // Red
                                            break;
                                        case 'hired':
                                            $statusClass = 'bg-success'; // Green, perhaps a slightly different shade if available
                                            break;
                                        case 'withdrawn':
                                            $statusClass = 'bg-dark'; // Darker gray/black for withdrawn
                                            break;
                                        default:
                                            $statusClass = 'bg-light text-dark'; // Default or 'New' status
                                            break;
                                    }
                                @endphp
                                <span class="badge rounded-pill {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $app->status ?? 'New')) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="p-3 text-muted text-center">No recent applications found.</div>
                    @endforelse
                </div>

                <div class="card-footer">
                    {{-- {{ route('admin.applications.index') }} --}}
                    <a href="{{ route('applications.recent_application') }}" class="btn btn-outline-primary btn-sm w-100">
                        View All Applications
                    </a>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('dashboard-scripts')
    <script>
        let trafficChart;
        const ctx = document.getElementById('siteTrafficChart').getContext('2d');

        function loadTrafficChart(days = 7) {
            fetch(`{{ route('admin.site_traffic') }}?days=${days}`)
                .then(res => res.json())
                .then(data => {
                    const chartData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'New User Signups',
                            data: data.visits,
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            fill: true,
                            tension: 0.4
                        }]
                    };

                    if (trafficChart) {
                        trafficChart.data = chartData;
                        trafficChart.update();
                    } else {
                        trafficChart = new Chart(ctx, {
                            type: 'line',
                            data: chartData,
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    }
                });
        }

        // Load default chart
        loadTrafficChart(7);

        // Handle dropdown change
        document.getElementById('trafficRange').addEventListener('change', function() {
            loadTrafficChart(this.value);
        });
    </script>
@endpush
