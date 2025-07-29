@extends('recruiter.layout.app')

@section('title', 'Job Post Page - ' )

@push('login_recruiter_styles')
    <style>
        .card {
            border-radius: 1rem;
        }

        .form-control {
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .btn-primary {
            border-radius: 0.5rem;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
        }

        #togglePassword {
            cursor: pointer;
            border-left: none;
            background: transparent;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .input-group-text {
            background-color: #fff;
            border-left: none;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center">Recruiter Login</h3>

                        @include('components.alert')

                        <form method="POST" action="{{ route('recruiter.login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                    value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    <span class="input-group-text" id="togglePassword">
                                        <i class="fas fa-eye" id="eyeIcon"></i>
                                    </span>
                                    @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check mb-0">
                                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                    <label class="form-check-label" for="remember">Remember me</label>
                                </div>

                                <a href="{{ route('recruiter.password.request') }}" class="text-primary small">
                                    <i class="bi bi-unlock-fill me-1" aria-hidden="true"></i>Forgot Your Password?
                                </a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('recruiter.register') }}">Don't have an account? Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('login_recruiter_scripts')
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                eyeIcon.classList.toggle('fa-eye-slash');
            });
        }
    </script>

@endpush
