@extends('layouts.app')

{{-- Page Title --}}
@section('title', 'Sign Up - ')

{{-- Signup Page Styles --}}
@section('user_signup_css')
    <style>
        body {
            background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
            min-height: 100vh;
        }

        .signup-card {
            border: none;
            border-radius: 1rem;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.15);
        }

        .signup-header {
            background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
            border-radius: 1rem 1rem 0 0;
            padding: 2rem 1rem 1rem 1rem;
            margin-bottom: 0;
        }

        .signup-icon {
            display: inline-block;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 50%;
            padding: 0.7rem 1.1rem;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
            color: #fff;
        }

        .signup-header h4 {
            font-weight: 700;
            letter-spacing: 1px;
            color: #fff;
        }

        .form-label {
            font-weight: 600;
            color: #495057;
        }

        .form-control {
            border-radius: 0.5rem;
            border: 1px solid #e3e6f0;
            box-shadow: none;
            transition: border-color 0.2s;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.1rem rgba(0, 123, 255, 0.15);
        }

        .btn-primary {
            background: linear-gradient(90deg, #007bff 0%, #00c6ff 100%);
            border: none;
            border-radius: 2rem;
            font-weight: 600;
            letter-spacing: 1px;
            transition: background 0.2s;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background: linear-gradient(90deg, #0056b3 0%, #007bff 100%);
        }

        .invalid-feedback {
            font-size: 0.95em;
        }

        .signup-links a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.2s;
        }

        .signup-links a:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        @media (max-width: 767.98px) {
            .signup-card {
                margin-top: 2rem;
            }
        }
    </style>
@endsection

{{-- Main Content --}}
@section('content')
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card signup-card shadow-sm">
                    <div class="signup-header text-center">
                        <span class="signup-icon">
                            <i class="bi bi-person-plus-fill" aria-hidden="true"></i>
                        </span>
                        <h4 class="mb-0 text-white">User Registration</h4>
                    </div>
                    <div class="card-body px-4 py-4">
                        {{-- Error Message --}}
                        @if (session('error'))
                            <div class="alert alert-danger d-flex align-items-center gap-2">
                                <i class="bi bi-exclamation-triangle-fill text-danger fs-5" aria-hidden="true"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        @endif
                        {{-- Registration Form --}}
                        <form method="POST" action="">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="bi bi-person-fill text-primary me-1" aria-hidden="true"></i>
                                    Full Name
                                </label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required autofocus
                                    placeholder="Enter your full name" aria-label="Full Name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="bi bi-envelope-at-fill text-primary me-1" aria-hidden="true"></i>
                                    Email Address
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required
                                    placeholder="Enter your email" aria-label="Email Address">
                                @error('email')
                                    @if ($message === 'The email has already been taken.')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                            <a href="{{ route('jobseeker.password.request', ['email' => old('email')]) }}"
                                                class="ml-2">Forgot Password?</a>
                                        </div>
                                    @else
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @endif
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label d-flex align-items-center gap-2">
                                    <i class="bi bi-shield-lock-fill text-primary fs-5" aria-hidden="true"></i>
                                    <span>Password</span>
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required placeholder="Enter your password"
                                        aria-label="Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0" style="cursor:pointer;"
                                            onclick="togglePassword('password', this)" title="Show/Hide Password">
                                            <i class="bi bi-eye-slash" id="togglePasswordIcon" aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-shield-lock text-primary me-1" aria-hidden="true"></i>
                                    Confirm Password
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required placeholder="Confirm your password"
                                        aria-label="Confirm Password">
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-white border-left-0" style="cursor:pointer;"
                                            onclick="togglePassword('password_confirmation', this)"
                                            title="Show/Hide Password">
                                            <i class="bi bi-eye-slash" id="togglePasswordConfirmIcon"
                                                aria-hidden="true"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">
                                    <i class="bi bi-telephone-fill text-primary me-1" aria-hidden="true"></i>
                                    Phone Number
                                </label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                    id="phone" name="phone" value="{{ old('phone') }}"
                                    placeholder="Enter your phone number" aria-label="Phone Number">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="profession" class="form-label">
                                    <i class="bi bi-briefcase-fill text-primary me-1" aria-hidden="true"></i>
                                    Profession
                                </label>
                                <input type="text" class="form-control @error('profession') is-invalid @enderror"
                                    id="profession" name="profession" value="{{ old('profession') }}"
                                    placeholder="Your profession" aria-label="Profession">
                                @error('profession')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary py-2 rounded-pill shadow-sm">
                                    <i class="bi bi-person-plus-fill me-2" aria-hidden="true"></i>Register
                                </button>
                            </div>
                        </form>
                        {{-- Login Link --}}
                        <div class="signup-links mt-4 text-center">
                            <a href="{{ route('user_login') }}"><i class="bi bi-unlock-fill me-1"
                                    aria-hidden="true"></i>Already have an account? Login</a>
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

{{-- Signup Page Scripts --}}
@section('user_signup_scripts')
    <script>
        // Toggle password visibility for password fields
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
