@php
    use Illuminate\Support\Str;

    $applications = \App\Models\JobApplication::with('job')->where('user_id', $user->id)->latest()->get();
@endphp

<div>
    <h5 class="fw-bold mb-3">My Jobs</h5>

    @if ($applications->isEmpty())
        <div class="alert alert-info">You have not applied to any jobs yet.</div>
    @else
        <ul class="list-group">
            @foreach ($applications as $app)
                @php
                    $status = $app->status ?? 'submitted';
                    $badgeColors = [
                        'submitted' => 'secondary',
                        'under_review' => 'info',
                        'shortlisted' => 'primary',
                        'interview' => 'warning',
                        'selected' => 'success',
                        'rejected' => 'danger',
                        'hired' => 'success',
                        'withdrawn' => 'dark',
                    ];
                    $color = $badgeColors[$status] ?? 'secondary';
                @endphp

                <li class="list-group-item d-flex justify-content-between align-items-start flex-column flex-md-row">
                    <div class="flex-grow-1 me-3">
                        <strong>{{ $app->job->title ?? '-' }}</strong> at {{ $app->job->company ?? '-' }} <br>
                        <span class="text-muted small">Applied on {{ $app->created_at->format('d M Y') }}</span><br>
                        <span
                            class="badge bg-{{ $color }} mt-1 text-capitalize">{{ str_replace('_', ' ', $status) }}</span>
                    </div>

                    <div class="btn-group mt-2 mt-md-0">
                        <a href="{{ route('job.full', $app->job->id) }}" class="btn btn-outline-primary btn-sm">View
                            Job</a>

                        @if (!in_array($status, ['withdrawn', 'rejected', 'hired']))
                            <form method="POST" action="{{ route('user.job.withdraw', $app->id) }}"
                                onsubmit="return confirm('Are you sure you want to withdraw this application?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">Withdraw</button>
                            </form>
                        @endif

                        @if (in_array($status, ['withdrawn', 'rejected']))
                            <form method="POST" action="{{ route('user.job.delete', $app->id) }}"
                                onsubmit="return confirm('Are you sure you want to permanently delete this application?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-dark btn-sm">Delete</button>
                            </form>
                        @endif
                    </div>
                    @php
                        $existingReview = \App\Models\Review::where('user_id', auth()->id())
                            ->where('job_id', $app->job->id)
                            ->first();
                    @endphp
                    @if ($status === 'hired' || $status === 'interview' || $status === 'selected' || $status === 'shortlisted')
                        @if (!$existingReview)
                            {{-- Write Review --}}
                            <form method="POST" action="{{ route('user.review.submit', $app->job->id) }}"
                                class="mt-3 p-3 border rounded shadow-sm bg-light">
                                @csrf
                                <div class="mb-2">
                                    <strong class="d-block mb-1">Rate this job:</strong>
                                    <div class="star-rating d-flex">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <input type="radio" name="rating" value="{{ $i }}"
                                                id="star{{ $app->id }}-{{ $i }}" required hidden>
                                            <label for="star{{ $app->id }}-{{ $i }}" class="me-1"
                                                style="cursor: pointer;">
                                                <i class="bi bi-star-fill text-secondary fs-5"
                                                    onmouseover="highlightStars(this, {{ $i }}, '{{ $app->id }}')"
                                                    onmouseout="unhighlightStars('{{ $app->id }}')"
                                                    onclick="selectStar('{{ $app->id }}', {{ $i }})"
                                                    id="star-icon-{{ $app->id }}-{{ $i }}"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <textarea name="review" class="form-control" placeholder="Write your experience or feedback..." rows="2"
                                        required></textarea>
                                </div>

                                <button class="btn btn-success btn-sm w-100">Submit Review</button>
                            </form>
                        @else
                            {{-- Already reviewed --}}
                            <div class="alert alert-success mt-3">
                                <strong>Thanks for your review!</strong><br>
                                You rated this job <strong>{{ $existingReview->rating }}/5</strong> stars.<br>
                                <em>"{{ $existingReview->review }}"</em>
                            </div>
                        @endif
                    @endif
                </li>
            @endforeach
        </ul>

    @endif
</div>

<script>
    function highlightStars(el, count, id) {
        for (let i = 1; i <= 5; i++) {
            const icon = document.getElementById(`star-icon-${id}-${i}`);
            if (i <= count) {
                icon.classList.remove('text-secondary');
                icon.classList.add('text-warning');
            } else {
                icon.classList.remove('text-warning');
                icon.classList.add('text-secondary');
            }
        }
    }

    function unhighlightStars(id) {
        const radios = document.querySelectorAll(`input[name="rating"]:checked`);
        const selected = [...radios].find(r => r.closest('form').action.includes(id));
        const value = selected ? parseInt(selected.value) : 0;
        highlightStars(null, value, id);
    }

    function selectStar(id, count) {
        document.getElementById(`star${id}-${count}`).checked = true;
        highlightStars(null, count, id);
    }

    // Automatically highlight the star if preselected
    document.addEventListener('DOMContentLoaded', () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            const id = form.action.split('/').pop();
            const checked = form.querySelector('input[name="rating"]:checked');
            if (checked) {
                highlightStars(null, checked.value, id);
            }
        });
    });
</script>
