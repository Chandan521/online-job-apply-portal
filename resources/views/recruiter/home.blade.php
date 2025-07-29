@extends('recruiter.layout.app')

@section('title', 'Home')

@section('content')
    <div class="container my-5">
        <!-- Section 1: Hero Poster -->
        <div class="row align-items-center mb-5">
            <div class="col-md-6">
                <h1 class="display-5 fw-bold">Let's hire your next<br>great candidate. Fast.</h1>
                <a href="{{ route('create_job_view') }}" class="btn btn-primary btn-lg mt-4">Post a Free Job</a>
                <p class="small text-muted mt-2">*Terms, conditions, quality standards and usage limits apply.</p>
            </div>
            <div class="col-md-6 text-center">
                <img src="https://img.freepik.com/free-vector/job-interview-process-hiring-new-employees-hr-specialist-cartoon-character-talking-new-candidatee-recruitment-employment-headhunting_335657-2680.jpg?semt=ais_hybrid&w=740"
                    alt="Hiring Illustration" class="img-fluid">
            </div>
        </div>

        <!-- Section 2: Steps with Shadow Box -->
        <div class="row bg-white shadow rounded p-4 mb-5">
            <div class="col-md-4">
                <h2 class="fw-bold">1<br>Create your free account</h2>
                <p>All you need is your email address to create an account and start building your job post.</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold">2<br>Build your job post</h2>
                <p>Add a title, description, and location to your job post and you're ready to go.</p>
            </div>
            <div class="col-md-4">
                <h2 class="fw-bold">3<br>Post your job</h2>
                <p>After posting, use our tools to find top talent efficiently.</p>
            </div>
        </div>

        <!-- Section 3: Save time and effort -->
        <div class="mb-5">
            <h2 class="fw-bold mb-3">Save time and effort in your recruitment journey.</h2>
            <p class="lead">Finding the best fit shouldn’t be a full-time job. {{ setting('site_name' ?? 'Name Not Set') }}'s simple and
                powerful tools help you source, screen, and hire faster.</p>
        </div>

        <!-- Section 4: Features Columns -->
        <div class="row mb-5">
            <div class="col-md-6">
                <div class="mb-4">
                    <h5><i class="bi bi-bullseye fs-4 text-primary"></i> Get more visibility</h5>
                    <p>Sponsor your job to ensure it gets seen by the right people.</p>
                </div>
                <div class="mb-4">
                    <h5><i class="bi bi-patch-check fs-4 text-primary"></i> Verify their abilities</h5>
                    <p>Add screener questions to test applicants’ skills.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-4">
                    <h5><i class="bi bi-people fs-4 text-primary"></i> Find quality applicants</h5>
                    <p>List required skills so relevant candidates apply.</p>
                </div>
                <div class="mb-4">
                    <h5><i class="bi bi-list-check fs-4 text-primary"></i> Organize your candidates</h5>
                    <p>Sort CVs, send messages, and schedule interviews — all on {{ config('app.name') }}.</p>
                </div>
            </div>
            <div class="text-center">
                <a href="#" class="btn btn-success btn-lg mt-3">Get Started</a>
                <p class="small text-muted mt-2">You control your posts 24/7 – edit, add, pause or close them anytime. <a
                        href="#">Learn more about posting</a>.</p>
            </div>
        </div>

        <!-- Section 5: Trusted by Companies -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">You're in good company.</h2>
            <p>Over 2,20,000 companies use {{ config('app.name') }} to hire. See why these companies trust us for recruiting
                top talent.</p>
            <div class="row mt-4">
                <div class="col-md-4 text-center">
                    <img src="https://1000logos.net/wp-content/uploads/2017/03/Nokia-Logo.png" class="mb-2 img-fluid"
                        alt="Company 1" style="height: 60px; object-fit: contain;">
                    <p>Helping the world’s largest restaurant business fill hard-to-recruit roles.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="https://1000logos.net/wp-content/uploads/2017/06/Font-Samsung-Logo.jpg" class="mb-2 img-fluid"
                        alt="Company 2" style="height: 60px; object-fit: contain;">
                    <p>One of the oldest universities leverages {{ config('app.name') }} for both academic and support
                        roles.</p>
                </div>
                <div class="col-md-4 text-center">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS63iW-drx9mgYHjGG6LpPRka6417HbE2sFVg&s"
                        class="mb-2 img-fluid" alt="Company 3" style="height: 60px; object-fit: contain;">
                    <p>A mobile leader reduces hiring cost with Sponsored Jobs on our platform.</p>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-white">
        <div class="container">
            <div class="row justify-content-center mb-2">
                <div class="col-md-auto">
                    <strong class="text-dark">{{ config('app.name', 'HireMe') }}</strong> &copy; {{ date('Y') }}. All
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
