@extends('recruiter.layout.dashboard_layout')
@section('title', 'Scheduled Interviews')
@section('content')

    <style>
        .interview-container {
            padding: 2rem 0;
        }

        .interview-table-holder {
            border-radius: 16px;
            background: #fff;
            overflow: hidden;
            box-shadow: 0 2px 24px rgba(0, 0, 0, 0.07);
        }

        .interview-table {
            min-width: 1200px;
        }

        .table-interviews thead th {
            background: #f4f7fb;
            /* font-weight: 700; */
            letter-spacing: 0.04em;
            /* font-size: 1.04em; */
            color: #374151;
            border-bottom: 2px solid #e3e6ea;
        }

        .table-interviews tbody tr {
            transition: box-shadow 0.2s;
            vertical-align: middle;
        }

        .table-interviews tbody tr:hover {
            background: #f9fafc;
            box-shadow: 0 2px 18px rgba(0, 0, 0, 0.04);
        }

        .badge-status {
            /* font-size: 0.92em; */
            padding: .32em 1.1em;
            border-radius: 1rem;
            /* font-weight: 600; */
            letter-spacing: 0.01em;
        }

        .badge-status.scheduled {
            background: #e3f2fd;
            color: #1967d2;
        }

        .badge-status.rescheduled {
            background: #fff8e1;
            color: #eab308;
        }

        .badge-status.completed {
            background: #e6f4ea;
            color: #2e7d32;
        }

        .badge-status.cancelled {
            background: #ffebee;
            color: #c62828;
        }

        .badge-status.expired {
            background: #f8d7da;
            color: #721c24;
        }

        .candidate-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e3e6ea;
            margin-right: 12px;
        }

        .candidate-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .candidate-name {
            /* font-weight: 600; */
            color: #22223b;
            /* font-size: 1.04em; */
        }

        .candidate-email {
            color: #6c757d;
            /* font-size: 0.96em; */
        }

        .job-title {
            /* font-weight: 600; */
            color: #283593;
            /* font-size: 1.01em; */
        }

        .job-location {
            color: #6c757d;
            /* font-size: 0.96em; */
        }

        .interview-actions .btn {
            min-width: 110px;
            margin-bottom: 3px;
        }

        .interview-actions .btn+form {
            margin-left: 0.5em;
        }

        .table-responsive {
            overflow-x: auto;
        }

        .sticky-header th {
            position: sticky;
            top: 0;
            z-index: 2;
        }

        /* Responsive - Stack columns on mobile */
        @media (max-width: 900px) {
            .interview-table-holder {
                padding: 0;
            }

            .table-interviews thead {
                display: none;
            }

            .table-interviews,
            .table-interviews tbody,
            .table-interviews tr,
            .table-interviews td {
                display: block;
                width: 100%;
            }

            .table-interviews tr {
                margin-bottom: 1.6em;
                border: 1px solid #e3e6ea;
                border-radius: 10px;
                box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            }

            .table-interviews td {
                padding: 0.8em 1em;
                border-bottom: 1px solid #f0f0f0;
                text-align: left;
            }

            .table-interviews td:before {
                content: attr(data-label);
                /* font-size: 0.93em; */
                /* font-weight: 700; */
                display: block;
                color: #888;
                margin-bottom: 3px;
            }

            .interview-actions .btn,
            .interview-actions form {
                width: 100%;
                margin-bottom: 7px;
            }
        }
    </style>


    <div class="container interview-container">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="fw-bold mb-0 text-primary">
                <i class="bi bi-calendar2-check me-2"></i>
                Scheduled Interviews
            </h2>
            <a href="{{ route('recruiter_dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Back to Dashboard
            </a>
        </div>

        <div class="interview-table-holder">
            <div class="table-responsive">
                <table class="table table-interviews interview-table mb-0 sticky-header align-middle">
                    <thead>
                        <tr>
                            <th>Candidate</th>
                            <th>Job Title</th>
                            <th>Date & Time</th>
                            <th>Mode</th>
                            <th>Location</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Action By</th>
                            <th>Meeting Link</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($interviews as $interview)
                            @php
                                $statusType = strtolower($interview->status);
                            @endphp
                            <tr>
                                <td data-label="Candidate">
                                    <div class="candidate-info">
                                        <img src="{{ $interview->application->user->profile_photo
                                            ? asset('storage/' . $interview->application->user->profile_photo)
                                            : 'https://ui-avatars.com/api/?name=' . urlencode($interview->application->user->name) . '&background=random' }}"
                                            class="candidate-avatar" alt="avatar">
                                        <div>
                                            <span
                                                class="candidate-name">{{ $interview->application->user->name }}</span><br>
                                            <span class="candidate-email">{{ $interview->application->user->email }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Job Title">
                                    <span class="job-title">{{ $interview->job->title }}</span><br>
                                    <span class="job-location">{{ $interview->job->location ?? '-' }}</span>
                                </td>
                                <td data-label="Date & Time">
                                    <span
                                        class="fw-semibold">{{ \Carbon\Carbon::parse($interview->interview_datetime)->format('d M Y, h:i A') }}</span>
                                    <br>
                                    <span class="text-muted small">
                                        {{ \Carbon\Carbon::parse($interview->interview_datetime)->diffForHumans() }}
                                    </span>
                                </td>
                                <td data-label="Mode">
                                    <span class="badge bg-light text-dark border">{{ ucfirst($interview->mode) }}</span>
                                </td>
                                <td data-label="Location">
                                    {{ $interview->location ?? '-' }}
                                </td>
                                <td data-label="Notes">
                                    <span class="text-wrap">{{ $interview->notes ?? '-' }}</span>
                                </td>
                                <td data-label="Status">
                                    <span class="badge badge-status {{ $statusType }}">
                                        {{ ucfirst($interview->status) }}
                                    </span>
                                </td>
                                <td data-label="Action By">
                                    @if ($interview->status === 'cancelled')
                                        <span class="text-danger small">By {{ ucfirst($interview->cancelled_by) }}</span>
                                    @elseif($interview->status === 'rescheduled')
                                        <span class="text-warning small">By
                                            {{ ucfirst($interview->rescheduled_by) }}</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td data-label="Meeting Link">
                                    @if ($interview->mode === 'online' && !empty($interview->meeting_link))
                                        <a href="{{ $interview->meeting_link }}" class="btn btn-sm btn-outline-primary"
                                            target="_blank">
                                            <i class="bi bi-camera-video"></i> Join
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td data-label="Actions" class="interview-actions">
                                    @if (!in_array($interview->status, ['cancelled', 'completed']))
                                        <button class="btn btn-sm btn-warning mb-1" data-bs-toggle="modal"
                                            data-bs-target="#rescheduleModal{{ $interview->id }}">
                                            <i class="bi bi-calendar-range"></i> Reschedule
                                        </button>
                                        <form method="POST"
                                            action="{{ route('recruiter.interviews.destroy', $interview->id) }}"
                                            style="display:inline-block;"
                                            onsubmit="return confirm('Are you sure to revoke this interview?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i> Revoke
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>

                            <!-- Reschedule Modal -->
                            <div class="modal fade" id="rescheduleModal{{ $interview->id }}" tabindex="-1"
                                aria-labelledby="rescheduleModalLabel{{ $interview->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form method="POST"
                                        action="{{ route('recruiter.interviews.update', $interview->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="rescheduleModalLabel{{ $interview->id }}">
                                                    Reschedule Interview
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label>Date & Time</label>
                                                    <input type="datetime-local" name="interview_datetime"
                                                        class="form-control"
                                                        value="{{ \Carbon\Carbon::parse($interview->interview_datetime)->format('Y-m-d\TH:i') }}"
                                                        required>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Mode</label>
                                                    <select name="mode" class="form-control" required>
                                                        <option value="online"
                                                            {{ $interview->mode === 'online' ? 'selected' : '' }}>Online
                                                        </option>
                                                        <option value="in-person"
                                                            {{ $interview->mode === 'in-person' ? 'selected' : '' }}>
                                                            In-Person</option>
                                                        <option value="phone"
                                                            {{ $interview->mode === 'phone' ? 'selected' : '' }}>Phone
                                                        </option>
                                                    </select>
                                                </div>
                                                <div class="mb-3">
                                                    <label>Location</label>
                                                    <input type="text" name="location" class="form-control"
                                                        value="{{ $interview->location }}">
                                                </div>
                                                <div class="mb-3">
                                                    <label>Notes</label>
                                                    <textarea name="notes" class="form-control">{{ $interview->notes }}</textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Save Changes</button>
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancel</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <i class="bi bi-emoji-frown display-5 text-muted"></i>
                                    <div class="mt-2">No interviews scheduled.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="mt-auto w-100 bg-light text-center py-3 text-muted small"
        style="position: fixed; left: 0; right: 0; bottom: 0; z-index: 100; border-top: 1px solid #ddd;">
        © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">
            | Empowering recruiters to find top talent with ease.
        </span>
    </footer>
@endsection
