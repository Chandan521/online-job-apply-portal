    @extends('recruiter.layout.dashboard_layout')

    @section('title', 'Job Applications')

    @section('content')
    <style>
        /* Light mode (default) */
body[data-theme="light"] {
    background-color: #fff;
    color: #000;
}

/* Dark mode */
body[data-theme="dark"] {
    background-color: #121212;
    color: #f0f0f0;
}

body[data-theme="dark"] .form-step-box {
    background-color: #1e1e1e;
    color: #fff;
}

body[data-theme="dark"] .form-control,
body[data-theme="dark"] .form-select {
    background-color: #2a2a2a;
    color: #fff;
    border-color: #444;
}

body[data-theme="dark"] .progress-bar {
    background-color: #4caf50;
}

body[data-theme="dark"] .btn-primary {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

body[data-theme="dark"] .btn-success {
    background-color: #198754;
    border-color: #198754;
}

    </style>
        <div class="container mt-4">
            <h2 class="mb-4">All Job Applications</h2>
            <div class="table-responsive">
                <form method="GET" action="{{ route('job_application.index') }}" class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">From Date</label>
                        <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">To Date</label>
                        <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="">All (except Withdrawn)</option>
                            @foreach (['under_review', 'shortlisted', 'interview', 'selected', 'rejected', 'hired'] as $status)
                                <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('job_application.export', array_merge(request()->all(), ['format' => 'csv'])) }}"
                            class="btn btn-outline-success">Export CSV</a>
                    </div>
                </form>
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Candidate</th>
                            <th>Job</th>
                            <th>Status</th>
                            <th>Applied On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $key => $app)
                            <tr>
                                <td>{{ $applications->firstItem() + $key }}</td>
                                <td>
                                    <strong>{{ $app->first_name }} {{ $app->last_name }}</strong><br>
                                    <small>{{ $app->email }}</small><br>
                                    <small>{{ $app->country_code }} {{ $app->phone }}</small>
                                </td>
                                <td>{{ $app->job->title ?? '-' }}</td>
                                <td>
                                    <form method="POST" action="{{ route('applications.status', $app->id) }}">
                                        @csrf
                                        @method('PUT')
                                        @php
                                            $allowedStatuses = [
                                                'under_review',
                                                'shortlisted',
                                                'interview',
                                                'selected',
                                                'rejected',
                                                'hired',
                                            ];
                                            $currentStatus = $app->status;
                                            $isWithdrawn = $currentStatus === 'withdrawn';
                                        @endphp

                                        @if ($isWithdrawn)
                                            <select class="form-select form-select-sm" disabled>
                                                <option selected disabled>Withdrawn</option>
                                            </select>
                                        @else
                                            <select name="status" class="form-select form-select-sm"
                                                onchange="this.form.submit()">
                                                {{-- Show current status if it's not allowed (like 'submitted') --}}
                                                @if (!in_array($currentStatus, $allowedStatuses))
                                                    <option value="{{ $currentStatus }}" selected disabled>
                                                        {{ ucwords(str_replace('_', ' ', $currentStatus)) }} (Locked)
                                                    </option>
                                                @endif

                                                {{-- Show allowed statuses --}}
                                                @foreach ($allowedStatuses as $status)
                                                    <option value="{{ $status }}"
                                                        {{ $currentStatus === $status ? 'selected' : '' }}>
                                                        {{ ucwords(str_replace('_', ' ', $status)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        @endif


                                    </form>
                                </td>
                                <td>{{ $app->created_at->format('d M Y') }}</td>
                                <td>
                                    <a href="{{ route('job_application.show', $app->id) }}"
                                        class="btn btn-sm btn-primary">View</a>

                                    @if ($app->job)
                                        <a href="{{ route('recruiter.jobs.show', $app->job->id) }}"
                                            class="btn btn-sm btn-info">View Job</a>
                                    @endif

                                    @if ($app->resume)
                                        <a href="{{ asset('/storage/' . ltrim($app->resume, '/')) }}"
                                            class="btn btn-sm btn-secondary" target="_blank">Resume</a>
                                    @endif

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No applications found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                

            </div>

            <div class="mt-3">
                {{ $applications->links() }}
            </div>
        </div>
    @endsection
