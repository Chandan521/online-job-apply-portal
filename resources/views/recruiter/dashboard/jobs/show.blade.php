@extends('recruiter.layout.dashboard_layout')

@section('title', 'View Job')

@section('content')

    <style>
        /* Light theme (default) */
        body[data-theme="light"] {
            background-color: #ffffff;
            color: #212529;
        }

        body[data-theme="light"] .table {
            background-color: #ffffff;
            color: #212529;
            border-color: #dee2e6;
        }

        body[data-theme="light"] .table th,
        body[data-theme="light"] .table td {
            background-color: #ffffff;
        }

        body[data-theme="light"] .badge {
            color: #fff;
        }

        /* Dark theme */
        body[data-theme="dark"] {
            background-color: #121212;
            color: #f1f1f1;
        }

        body[data-theme="dark"] .container {
            background-color: #1e1e1e;
        }

        body[data-theme="dark"] .table {
            background-color: #1e1e1e;
            color: #f1f1f1;
            border-color: #444;
        }

        body[data-theme="dark"] .table th,
        body[data-theme="dark"] .table td {
            background-color: #1e1e1e;
            border-color: #444;
        }

        body[data-theme="dark"] .badge.bg-primary {
            background-color: #0d6efd;
            color: #fff;
        }

        body[data-theme="dark"] .badge.bg-success {
            background-color: #198754;
            color: #fff;
        }

        body[data-theme="dark"] .badge.bg-secondary {
            background-color: #6c757d;
            color: #fff;
        }

        body[data-theme="dark"] .img-thumbnail {
            background-color: #2c2c2c;
            border-color: #444;
        }

        body[data-theme="dark"] .btn-secondary {
            background-color: #444;
            color: #fff;
            border-color: #555;
        }

        body[data-theme="dark"] a {
            color: #0d6efd;
        }
    </style>


    <div class="container mt-4">
        <h2 class="mb-4">Job Details</h2>

        <table class="table table-bordered">
            <tr>
                <th>Title</th>
                <td>{{ $job->title ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Company</th>
                <td>{{ $job->company ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Location</th>
                <td>{{ $job->location ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Salary</th>
                <td>{{ $job->salary ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Shift</th>
                <td>{{ $job->shift ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Type</th>
                <td>{{ $job->type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    @php $status = $job->status ?? 'inactive'; @endphp
                    <span class="badge bg-{{ $status === 'active' ? 'success' : 'secondary' }}">
                        {{ ucfirst($status) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Is Approved By Admin</th>
                <td>
                    @php $isApproved = $job->is_approved ?? false; @endphp
                    <span class="badge bg-{{ $isApproved ? 'success' : 'secondary' }}">
                        {{ $isApproved ? 'Approved' : 'Not Approved' }}
                    </span>
                </td>
            </tr>

            <tr>
                <th>Skills</th>
                <td>
                    @foreach (json_decode($job->skills ?? '[]', true) ?? [] as $skill)
                        <span class="badge bg-primary">{{ $skill }}</span>
                    @endforeach

                </td>
            </tr>
            <tr>
                <th>Views</th>
                <td>{{ $job->views ?? 0 }}</td>
            </tr>
            <tr>
                <th>Posted On</th>
                <td>{{ optional($job->created_at)->format('d M Y') ?? 'N/A' }}</td>
            </tr>
            <tr>
                <th>Cover Image</th>
                <td>
                    @if (!empty($job->cover_image))
                        <img src="{{ asset($job->cover_image) }}" width="200" class="img-thumbnail">
                    @else
                        <em>No image uploaded.</em>
                    @endif
                </td>
            </tr>
        </table>
        @if ($job->additionalQuestions->isNotEmpty())
            <h4>Additional Questions</h4>
            <ul>
                @foreach ($job->additionalQuestions as $question)
                    <li>
                        <strong>{{ $question->question }}</strong> ({{ ucfirst($question->type) }})
                        @if ($question->is_required)
                            <span class="text-danger">*</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        @if ($job->reviews->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">User Reviews ({{ $job->reviews->count() }})</h5>
                </div>
                <div class="card-body">
                    @foreach ($job->reviews as $review)
                        <div class="mb-3 border-bottom pb-2">
                            <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                            <div class="text-warning mb-1">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                @endfor
                            </div>
                            <p>{{ $review->review }}</p>
                            <small class="text-muted">Reviewed on {{ $review->created_at->format('d M Y') }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-info mt-4">No reviews yet for this job.</div>
        @endif



        <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>

    </div>
@endsection
