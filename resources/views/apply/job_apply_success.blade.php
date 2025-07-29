@extends('layouts.app')

@section('title', 'Application Submitted - ')

@php
    $email = request()->query('email', 'chandanmondal0021@gmail.com'); // default for testing
@endphp

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">

            {{-- Success Icon --}}
            <div class="mb-4">
                <img src="https://cdn-icons-png.flaticon.com/512/845/845646.png" alt="Success" width="100" height="100">
            </div>

            {{-- Title --}}
            <h3 class="fw-bold text-success mb-3">Your application has been submitted!</h3>

            {{-- Box Section --}}
            <div class="border rounded shadow-sm bg-light p-4 mb-4">
                <div class="mb-3">
                    <img src="https://cdn-icons-png.flaticon.com/512/190/190411.png" alt="Email" width="24" height="24" class="me-2">
                    You will get an email confirmation at <strong>{{ $email }}</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="text-start">
                        <strong>Keep track of your applications</strong><br>
                        <small class="text-muted">To keep track of your applications, go to MyJobs.</small>
                    </div>
                    <div class="text-end">
                        <a href="{{ url('user/settings?tab=jobs') }}" class="btn btn-outline-primary">
                            MyJobs <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Return to Job Search --}}
            <a href="{{ route('home') }}" class="btn btn-primary w-100 mb-4">
                Return to Job Search
            </a>

            {{-- Support and reCAPTCHA Disclaimer --}}
            <p class="small text-muted mb-1">
                Having an issue with this application? <a href="#">Tell us more</a>
            </p>
            <p class="small text-muted">
                This site is protected by reCAPTCHA and the Google
                <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Privacy Policy</a> and
                <a href="https://policies.google.com/terms" target="_blank" rel="noopener">Terms of Service</a> apply.
            </p>

        </div>
    </div>
</div>
@endsection

@section('footer')
<footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
    © {{ date('Y') }} <strong>{{ setting('site_name', config('app.name'))  }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
</footer>
@endsection
