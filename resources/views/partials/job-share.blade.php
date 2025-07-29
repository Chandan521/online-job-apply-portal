@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Salaries - ')

{{-- Salaries Page Styles --}}
@section('salaries_css')
    <style>
        /* Modernized salaries styles */
        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 2rem;
        }

        .spinner-border {
            width: 4rem;
            height: 4rem;
        }

        h3 {
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }

        .text-muted {
            color: #6c757d !important;
        }

        footer {
            background-color: #f8f9fa;
            padding: 1rem 0;
            border-top: 1px solid #e9ecef;
        }

        footer .text-muted {
            margin-bottom: 0;
        }
    </style>
@endsection

{{-- Main Content --}}
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 mb-4">
                    <div class="card-body p-5">
                        <div class="d-flex align-items-center mb-4">
                            @if (!empty($job->company_logo))
                                <img src="{{ filter_var($job->company_logo, FILTER_VALIDATE_URL) ? $job->company_logo : (Illuminate\Support\Facades\Storage::exists($job->company_logo) ? Storage::url($job->company_logo) : asset($job->company_logo)) }}"
                                    alt="{{ $job->company }} Logo" class="rounded me-3"
                                    style="width: 64px; height: 64px; object-fit: contain; background: #f8f9fa; border: 1px solid #eee;">
                            @else
                                <div class="rounded bg-light d-flex align-items-center justify-content-center me-3"
                                    style="width: 64px; height: 64px; border: 1px solid #eee;">
                                    <i class="bi bi-building fs-2 text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h2 class="mb-1">{{ $job->title }}</h2>
                                <h5 class="text-muted mb-0">{{ $job->company }}</h5>
                                <div class="small text-secondary"><i class="bi bi-geo-alt"></i> {{ $job->location }}</div>
                            </div>
                        </div>
                        <div class="mb-3">
                            @if ($job->type)
                                @foreach (json_decode($job->type, true) as $type)
                                    <span class="badge bg-primary me-2">{{ $type }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif

                            @if ($job->shift)
                                @foreach (json_decode($job->shift, true) as $shift)
                                    <span class="badge bg-secondary me-2">{{ $shift }}</span>
                                @endforeach
                            @else
                                <span class="badge bg-secondary">N/A</span>
                            @endif

                            <span class="badge bg-success">{{ $job->salary ?? 'N/A' }}</span>
                        </div>
                        <div class="mb-3">
                            <strong>Skills:</strong>
                            @if ($job->skills)
                                @foreach (explode(',', $job->skills) as $skill)
                                    <span class="badge bg-info text-dark me-1">{{ trim($skill) }}</span>
                                @endforeach
                            @else
                                <span class="text-muted">Not specified</span>
                            @endif

                        </div>
                        <div class="mb-4">
                            <strong>Description:</strong>
                            <div class="mt-1">{{ $job->description ?? 'No description provided.' }}</div>
                            @if ($job->requirements)
                                <div class="mt-3"><strong>Requirements:</strong><br><span
                                        class="text-muted">{!! nl2br(e($job->requirements)) !!}</span></div>
                            @endif
                            @if ($job->responsibilities)
                                <div class="mt-3"><strong>Responsibilities:</strong><br><span
                                        class="text-muted">{!! nl2br(e($job->responsibilities)) !!}</span></div>
                            @endif
                            @if ($job->benefits)
                                <div class="mt-3"><strong>Benefits:</strong><br><span
                                        class="text-muted">{!! nl2br(e($job->benefits)) !!}</span></div>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            <a href="{{ url('/job/' . $job->id . '/view') }}" class="btn btn-outline-primary"><i
                                    class="bi bi-eye"></i> Full Job View</a>
                            <a href="{{ url('/job-detail/' . $job->id) }}" class="btn btn-outline-secondary"><i
                                    class="bi bi-arrow-left"></i> Back to Job</a>
                        </div>
                    </div>
                </div>
                <div class="text-center p-4 border rounded bg-light shadow-sm">
                    <h4 class="mb-3"><i class="bi bi-share-fill"></i> Share this Job</h4>
                    <p class="mb-4">Share <strong>{{ $job->title }}</strong> at <strong>{{ $job->company }}</strong>
                        with others:</p>
                    <div class="d-flex justify-content-center gap-3 mb-3 flex-wrap">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/job/' . $job->id . '/view')) }}"
                            target="_blank" class="btn btn-primary"><i class="bi bi-facebook"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url('/job/' . $job->id . '/view')) }}&text={{ urlencode($job->title . ' at ' . $job->company) }}"
                            target="_blank" class="btn btn-info text-white"><i class="bi bi-twitter"></i> Twitter</a>
                        <a href="https://wa.me/?text={{ urlencode($job->title . ' at ' . $job->company . ' ' . url('/job/' . $job->id . '/view')) }}"
                            target="_blank" class="btn btn-success"><i class="bi bi-whatsapp"></i> WhatsApp</a>
                        <button class="btn btn-secondary"
                            onclick="navigator.clipboard.writeText('{{ url('/job/' . $job->id . '/view') }}');this.textContent='Copied!'; setTimeout(()=>this.textContent='Copy Link', 2000)"><i
                                class="bi bi-clipboard"></i> Copy Link</button>
                    </div>
                    <div class="small text-muted mt-2">Link copied will open the full job view page.</div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Footer Section --}}
@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong>{{ config('app.name', 'HireMe') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection
