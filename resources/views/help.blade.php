@extends('layouts.app')

@section('title', 'Help')

@section('content')
    <div class="container my-5">

        {{-- Intro Text --}}
        <div class="text-center mb-4">
            <h4>If you are looking for help, you are in the right place</h4>
        </div>

        {{-- Help Boxes Row --}}
        <div class="row g-4 mb-5">
            <div class="col-md-6">
                <div class="p-4 border rounded shadow-sm h-100">
                    <h5 class="fw-bold">Help for jobseekers</h5>
                    <p class="text-muted">
                        Got a question or need help using <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>? Our Jobseeker Help
                        Center is the place to start.
                        Whether it's about setting up an account, using a product or service, or facing a problem head-on,
                        browse our FAQs or read our how-to articles.
                        Your problem is our priority, no matter how simple or complex.
                    </p>
                    <a href="#" class="text-decoration-none fw-semibold">Jobseeker Help Center →</a>
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-4 border rounded shadow-sm h-100">
                    <h5 class="fw-bold">Help for employers</h5>
                    <p class="text-muted">
                        Looking to hire? The Employer Help Center is for companies and recruiters with professional
                        <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong> accounts
                        and includes technical support and guides for using  {{ setting('site_name' ?? 'Name Not Set') }}'s suite of tools for
                        finding, interviewing and hiring candidates.
                    </p>
                    <a href="#" class="text-decoration-none fw-semibold">Employer Help Center →</a>
                </div>
            </div>
        </div>

        {{-- Full Width Gray Support Section --}}
        @include('components.support')

    </div>
@endsection

@section('footer')
    <footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
        © {{ date('Y') }} <strong> {{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
        <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
    </footer>
@endsection
