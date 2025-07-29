<div>
    <h5 class="fw-bold mb-3">My Reviews</h5>

    @if ($reviews->isEmpty())
        <div class="alert alert-info">You have not submitted any reviews yet.</div>
    @else
        <ul class="list-group">
            @foreach ($reviews as $review)
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <strong>{{ $review->job->title ?? 'Job' }}</strong>
                            <div class="text-muted small">
                                {{ $review->job->company ?? '' }}
                                &mdash; Rated: 
                                <span class="text-warning">
                                    {{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}
                                </span>
                            </div>
                            <p class="mb-2 mt-2">{{ $review->review }}</p>

                            <a href="{{ route('job.full', $review->job->id) }}" class="btn btn-sm btn-outline-primary">
                                View Job
                            </a>
                        </div>
                        <div class="text-muted small text-end ms-3">
                            {{ $review->created_at->format('d M Y') }}
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
