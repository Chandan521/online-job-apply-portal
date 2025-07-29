@extends('recruiter.layout.app')

@section('title', 'Products')

@section('content')
    <div class="container my-5">
        {{-- Hero Section --}}
    <section class="bg-light py-5 border-bottom">
        <div class="container text-center">
            <h1 class="display-5 fw-bold text-dark">Hire the right people faster</h1>
            <p class="lead text-secondary">Explore our solutions to attract, engage, and hire top talent confidently.</p>
        </div>
    </section>

    {{-- Products Section --}}
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row g-4">

                {{-- Job Posting --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Job Posting</h5>
                            <p class="card-text text-secondary">Reach millions of candidates by posting jobs on our platform.</p>
                            <a href="{{ route('hire') }}" class="btn btn-outline-primary">Post a Job</a>
                        </div>
                    </div>
                </div>

                {{-- Resume Search --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Resume Search</h5>
                            <p class="card-text text-secondary">Search, filter, and directly connect with job seekers.</p>
                            <a href="{{ route('recruiter_dashboard') }}" class="btn btn-outline-primary">Find CVs</a>
                        </div>
                    </div>
                </div>

                {{-- Branding & Promotion --}}
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Employer Branding</h5>
                            <p class="card-text text-secondary">Promote your company to attract top-quality applicants.</p>
                            <a href="{{ route('resources') }}" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>

                {{-- Add More Products Below --}}
                {{-- Example: Candidate Assessments, Sponsored Jobs, etc. --}}
            </div>
        </div>
    </section>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-white">
        <div class="container">
            <div class="row justify-content-center mb-2">
                <div class="col-md-auto">
                    <strong class="text-dark">{{ setting('site_name' ?? 'Name Not Set') }}</strong> &copy; {{ date('Y') }}. All
                    rights reserved.
                </div>
                <div class="col-md-auto">
                    <span class="d-block d-md-inline mt-2 mt-md-0">
                        | Empowering recruiters to find the right talent with confidence.
                    </span>
                </div>
            </div>
            <div class="small text-secondary">
                <span class="d-block d-md-inline">Need help? Visit our <a href="{{ route('pages.show', 'help') }}"
                        class="text-decoration-none text-primary">Employer Help Center</a>.</span>
            </div>
        </div>
    </footer>
@endsection
