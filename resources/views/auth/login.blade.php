@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Login - User ' )

{{-- Login Page Styles --}}
@section('user_login_css')
<style>
        /* Body background gradient */
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
        }

        /* Login card styles */
        .login-card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 6px 32px 0 rgba(0, 0, 0, 0.08);
            background: #fff;
        }

        /* Header styles for the login section */
        .login-header {
            background: linear-gradient(90deg, #0ea5e9 60%, #6366f1 100%);
            border-radius: 1.25rem 1.25rem 0 0;
            padding: 2rem 1rem 1.25rem 1rem;
        }

        /* Title styles in the login header */
        .login-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
        }

        /* Icon styles in the login header */
        .login-icon {
            font-size: 2.5rem;
            color: #fff;
            margin-bottom: 0.5rem;
        }

        /* Form label styles */
        .form-label {
            font-weight: 500;
            color: #334155;
        }

        /* Focus styles for form controls */
        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, .15);
        }


        /* Primary button hover styles */
        .btn-primary:hover {
            background: linear-gradient(90deg, #0284c7 60%, #4f46e5 100%);
        }

        /* Link styles in the login section */
        .login-links a {
            color: #0ea5e9;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }

        /* Hover styles for links in the login section */
        .login-links a:hover {
            color: #6366f1;
            text-decoration: underline;
        }

        /* Danger alert styles */
        .alert-danger {
            border-radius: 0.5rem;
        }
    </style>
@endsection

{{-- Main Content --}}
@section('content')
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card login-card shadow-sm">
                <div class="login-header text-center">
                    <span class="login-icon">
                        <i class="bi bi-person-circle" aria-hidden="true"></i>
                    </span>
                    <h4 class="mb-0 text-white">User Login</h4>
                </div>
                <div class="card-body px-4 py-4">
                    {{-- Error Message --}}
                    @if (session('error'))
                        <div class="alert alert-danger d-flex align-items-center gap-2">
                            <i class="bi bi-exclamation-triangle-fill text-danger fs-5" aria-hidden="true"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                    {{-- Login Form --}}
                    <form method="POST" action="">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-at-fill text-primary me-1" aria-hidden="true"></i>
                                Email Address
                            </label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email" aria-label="Email Address">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label d-flex align-items-center gap-2">
                                <i class="bi bi-shield-lock-fill text-primary fs-5" aria-hidden="true"></i>
                                <span>Password</span>
                            </label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required placeholder="Enter your password" aria-label="Password">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-white border-left-0" style="cursor:pointer;" onclick="togglePassword('password', this)" title="Show/Hide Password">
                                        <i class="bi bi-eye-slash" id="togglePasswordIcon" aria-hidden="true"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 rounded-pill shadow-sm">
                                <i class="bi bi-box-arrow-in-right me-2" aria-hidden="true"></i>Login
                            </button>
                        </div>
                    </form>
                    {{-- Forgot Password & Register Links --}}
                    <div class="login-links mt-4 text-center">
                        <a href="{{ route('jobseeker.password.request') }}"><i class="bi bi-unlock-fill me-1" aria-hidden="true"></i>Forgot Your Password?</a>
                    </div>
                    <div class="login-links mt-2 text-center">
                        <span>Don't have an account?</span>
                        <a href="{{ route('user_signup') }}" class="ms-1"><i class="bi bi-person-plus-fill me-1" aria-hidden="true"></i>Register</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

{{-- Footer Section --}}
@section('footer')
<footer class="text-center text-muted py-4 mt-auto border-top small bg-light">
    © {{ date('Y') }} <strong>{{ setting('site_name' ?? 'Name Not Set') }}</strong>. All rights reserved.
    <span class="d-block d-md-inline mt-1 mt-md-0">| Built with ❤️ for job seekers and employers.</span>
</footer>
@endsection

{{-- Login Page Scripts --}}
@section('user_login_scripts')
<script>
    // Toggle password visibility for password field
    function togglePassword(fieldId, el) {
        const input = document.getElementById(fieldId);
        const icon = el.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bi-eye-slash');
            icon.classList.add('bi-eye');
        } else {
            input.type = 'password';
            icon.classList.remove('bi-eye');
            icon.classList.add('bi-eye-slash');
        }
    }
</script>
@endsection
