@extends('recruiter.layout.dashboard_layout')
@section('title', 'All Applied Users')
@section('content')
    <div class="container-fluid p-0">
        <h2 class="fw-bold mb-4">All Applicants to My Jobs</h2>
        <div class="table-responsive rounded shadow-sm">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>User Info</th>
                        <th>Job Title</th>
                        <th>Status</th>
                        <th>Applied At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                        @php
                            $application = $user->jobApplications->first(); // latest application to your job
                            $isBlocked = \App\Models\UserBlock::where('from_user_id', auth()->id())
                                ->where('to_user_id', $user->id)
                                ->exists();
                        @endphp
                        <tr>
                            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
                            <td>
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6c757d&color=fff&size=50' }}"
                                    alt="Profile" class="rounded-circle" width="65" height="65">

                            </td>
                            <td>
                                <div class="fw-semibold">{{ $user->name }}</div>
                                <div class="text-muted small">{{ $user->email }}</div>
                                <div class="small text-muted mt-1">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    {{ $user->city ?? '-' }}, {{ $user->country ?? '-' }}
                                </div>
                                @if ($user->linkedin_url)
                                    <div>
                                        <a href="{{ $user->linkedin_url }}" target="_blank" class="text-decoration-none">
                                            <i class="fab fa-linkedin text-primary"></i> LinkedIn
                                        </a>
                                    </div>
                                @endif
                            </td>
                            <td>
                                {{ $application->job->title ?? 'N/A' }}
                                <div class="small text-muted">#{{ $application->job_id ?? '-' }}</div>
                            </td>
                            <td>
                                <span
                                    class="badge bg-info text-dark text-uppercase">{{ ucfirst($application->status ?? '-') }}</span>
                            </td>
                            <td>
                                <span
                                    class="small text-muted">{{ $application->created_at->format('M d, Y H:i') ?? '-' }}</span>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-envelope"></i> Contact
                                    </a>
                                    @if ($application && $application->resume)
                                        <a href="{{ asset('storage/' . $application->resume) }}" target="_blank"
                                            class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-file-pdf"></i> Resume
                                        </a>
                                    @endif
                                    <form method="POST" action="{{ route('users.report', $user->id) }}"
                                        onsubmit="return confirm('Are you sure you want to report this user?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm">
                                            <i class="fas fa-flag"></i> Report
                                        </button>
                                    </form>
                                    @if (!$isBlocked)
                                        <a href="{{ route('users.block', $user->id) }}"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Block this user for your jobs?');">
                                            <i class="fas fa-user-slash"></i> Block
                                        </a>
                                    @else
                                        <a href="{{ route('users.unblock', $user->id) }}"
                                            class="btn btn-outline-success btn-sm"
                                            onclick="return confirm('Unblock this user for your jobs?');">
                                            <i class="fas fa-user-check"></i> Unblock
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">
                                <i class="fas fa-user-friends fa-2x mb-2"></i>
                                <br>No applications found for your jobs.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="my-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
