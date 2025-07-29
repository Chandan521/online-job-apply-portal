@extends('layouts.app')

@push('styles')
<style>
    .interview-card {
        transition: box-shadow 0.2s;
        border-radius: 14px;
        border: 1px solid #e3e6ea;
        box-shadow: 0 2px 16px rgba(0,0,0,0.05);
        margin-bottom: 2rem;
        overflow: hidden;
    }
    .interview-card.expired { border-color: #ffe0e0; }
    .interview-card.completed { border-color: #e5fbe5; }
    .interview-card.rescheduled { border-color: #fff5e0; }
    .interview-card.cancelled { border-color: #ffe8e8; }
    .card-status-badge {
        font-size: .98em;
        border-radius: 1rem;
        padding: .3em 1em;
    }
    .card-status-badge.scheduled { background: #e7f4ff; color: #1877f2; }
    .card-status-badge.rescheduled { background: #fffbe7; color: #eab308; }
    .card-status-badge.completed { background: #e7faed; color: #28a745; }
    .card-status-badge.cancelled { background: #fde8e8; color: #dc3545; }
    .card-status-badge.expired { background: #f8d7da; color: #721c24; }
    .interview-action-btn { min-width: 130px; }
</style>
@endpush

@section('content')
<div class="container py-4">
    <h2 class="mb-4 fw-bold"><i class="bi bi-calendar2-week me-2"></i>Your Scheduled Interviews</h2>
    @php $now = \Carbon\Carbon::now(); @endphp
    @forelse ($interviews as $interview)
        @php
            $interviewTime = \Carbon\Carbon::parse($interview->interview_datetime);
            $isPast = $now->gt($interviewTime);
            $statusType = $isPast ? 'expired' : strtolower($interview->status);
        @endphp

        <div class="interview-card {{ $statusType }}">
            <div class="card-body row align-items-center py-4 px-4">
                <div class="col-lg-7 col-md-8">
                    <h5 class="card-title mb-2 text-primary fw-bold">
                        <i class="bi bi-briefcase-fill me-2"></i>
                        {{ $interview->job->title }}
                    </h5>
                    <div class="mb-2 text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>
                        {{ $interviewTime->format('D, d M Y') }}
                        <span class="mx-2">|</span>
                        <i class="bi bi-clock me-1"></i>
                        {{ $interviewTime->format('h:i A') }}
                        <span class="mx-2">|</span>
                        @if($interview->mode === 'online' && !empty($interview->meeting_link))
                            <i class="bi bi-camera-video text-primary"></i> Online Interview
                        @else
                            <i class="bi bi-geo-alt text-secondary"></i> {{ ucfirst($interview->mode) }}
                        @endif
                    </div>
                    @if ($interview->location)
                        <div class="mb-1"><strong>Location:</strong> {{ $interview->location }}</div>
                    @endif
                    @if ($interview->notes)
                        <div class="mb-1"><strong>Notes:</strong> {{ $interview->notes }}</div>
                    @endif

                    <div class="mt-3">
                        <span class="fw-semibold">Status:</span>
                        <span class="card-status-badge {{ $statusType }}">
                            @if ($isPast)
                                Expired
                            @else
                                {{ ucfirst($interview->status) }}
                            @endif
                        </span>
                        @if(!$isPast && $interview->status === 'cancelled')
                            <span class="text-danger ms-2 small">By {{ ucfirst($interview->cancelled_by) }}</span>
                        @elseif(!$isPast && $interview->status === 'rescheduled')
                            <span class="text-warning ms-2 small">By {{ ucfirst($interview->rescheduled_by) }}</span>
                        @endif
                        <span class="ms-2 text-muted small">Scheduled by {{ $interview->recruiter->name ?? 'Recruiter' }}</span>
                    </div>
                </div>
                <div class="col-lg-5 col-md-4 text-md-end mt-4 mt-md-0">
                    @if (!$isPast && $interview->mode === 'online' && !empty($interview->meeting_link))
                        <a href="{{ $interview->meeting_link }}" target="_blank" class="btn btn-primary interview-action-btn mb-2">
                            <i class="bi bi-camera-video"></i> Join Online
                        </a>
                    @endif
                    @if (!$isPast && in_array($interview->status, ['scheduled', 'rescheduled']))
                        <a href="{{ route('interviews.reschedule', $interview->id) }}"
                           class="btn btn-outline-warning interview-action-btn mb-2">
                            <i class="bi bi-calendar-range"></i> Reschedule
                        </a>
                        <form action="{{ route('interviews.cancel', $interview->id) }}" method="POST" class="d-inline-block"
                              onsubmit="return confirm('Are you sure you want to cancel this interview?');">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger interview-action-btn mb-2">
                                <i class="bi bi-x-lg"></i> Cancel
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @if ($isPast)
                <div class="card-footer bg-danger bg-opacity-10 text-danger text-center fw-semibold">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    This interview has expired. Please contact your recruiter if you need to reschedule.
                </div>
            @elseif ($interview->status === 'cancelled')
                <div class="card-footer bg-danger bg-opacity-10 text-danger text-center fw-semibold">
                    <i class="bi bi-x-circle me-1"></i>
                    This interview was cancelled by <b>{{ ucfirst($interview->cancelled_by) }}</b>.
                </div>
            @elseif ($interview->status === 'rescheduled')
                <div class="card-footer bg-warning bg-opacity-10 text-warning text-center fw-semibold">
                    <i class="bi bi-arrow-repeat me-1"></i>
                    This interview was rescheduled by <b>{{ ucfirst($interview->rescheduled_by) }}</b>.
                </div>
            @endif
        </div>
    @empty
        <div class="alert alert-warning text-center py-5">
            <i class="bi bi-calendar-x display-4 mb-3"></i>
            <div class="fw-semibold fs-5">You have no scheduled interviews yet.</div>
            <div class="text-muted">Once a recruiter schedules an interview, you will see the details here.</div>
        </div>
    @endforelse
</div>
@endsection