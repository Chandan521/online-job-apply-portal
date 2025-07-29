<div class="container py-5">
    <h2 class="mb-4">Notifications</h2>

    @forelse($notifications as $note)
        <div class="alert alert-{{ $note->is_read ? 'secondary' : 'info' }} d-flex justify-content-between align-items-start">
            <div>
                <strong>{{ $note->title }}</strong><br>
                {{ $note->message }}
                <small class="text-muted d-block">
                    {{ \Carbon\Carbon::parse($note->created_at)->diffForHumans() }}
                </small>
            </div>
            <div class="ms-3 d-flex flex-column gap-1">
                @if(!$note->is_read)
                    <form action="{{ route('notifications.markRead', $note->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button class="btn btn-sm btn-outline-primary">Mark as Read</button>
                    </form>
                @endif
                <form action="{{ route('notifications.delete', $note->id) }}" method="POST" onsubmit="return confirm('Delete this notification?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                </form>
            </div>
        </div>
    @empty
        <div class="alert alert-secondary">No notifications yet.</div>
    @endforelse
</div>
