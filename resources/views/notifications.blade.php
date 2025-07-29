@extends('layouts.app')
@section('title', 'Notifications')
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <h4 class="mb-4 fw-bold text-center">🔔 Notifications</h4>

                @if ($notifications->isEmpty())
                    <div class="text-center text-muted">
                        <i class="bi bi-bell-slash-fill fs-1" aria-hidden="true"></i>
                        <p class="mt-3">You're all caught up! No new notifications.</p>
                    </div>
                @else
                    <div class="row g-3">
                        @foreach ($notifications as $notify)
                            <div class="col-12 notification-item" id="notification-{{ $notify->id }}">
                                <div class="card border-0 shadow-sm rounded-3">
                                    <div
                                        class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-2">
                                        <div class="d-flex flex-column" onclick="markAsRead({{ $notify->id }})"
                                            style="cursor: pointer;">
                                            <h6 class="card-title mb-1 text-primary">
                                                <i class="bi bi-info-circle-fill me-1"></i>
                                                {{ $notify->title ?? 'Notification' }}
                                            </h6>
                                            <p class="card-text text-muted small mb-1">
                                                {!! $notify->message ?? 'No details available.' !!}</p>

                                            @if (!empty($notify->company))
                                                <span class="badge bg-secondary small">
                                                    <i class="bi bi-building me-1"></i> {{ $notify->company }}
                                                </span>
                                            @endif
                                        </div>

                                        <div
                                            class="text-muted small text-end mt-2 mt-md-0 d-flex flex-column align-items-end gap-2">
                                            <div>
                                                <i
                                                    class="bi bi-clock me-1"></i>{{ \Carbon\Carbon::parse($notify->created_at)->diffForHumans() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif

            </div>
        </div>
    </div>

@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection


@push('notification_style')
    <style>
        @media (max-width: 576px) {
            .card-body {
                padding: 1rem 1.25rem;
            }
        }
    </style>
@endpush


@push('notification_scripts')
    <script>
    function markAsRead(id) {
        fetch(`/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(data => {
            if (data.status === 'success') {
                const el = document.getElementById(`notification-${id}`);
                if (el) el.remove(); // Optional: fade/remove the notification
            }
        });
    }
</script>

@endpush
