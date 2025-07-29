@extends('recruiter.layout.app')

@section('title', 'Job Post Page - ')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h3 class="mb-4 text-center">Recruiter Register</h3>
                        <form method="POST" action="{{ route('recruiter.register.submit') }}">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    @if ($message === 'The email has already been taken.')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                            <a href="{{ route('recruiter.password.request', ['email' => old('email')]) }}"
                                                class="ml-2">Forgot Password?</a>
                                        </div>
                                    @else
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @endif
                                @enderror
                            </div>

                            <!-- Company Name -->
                            <div class="mb-3">
                                <label for="company" class="form-label">Company Name</label>
                                <input type="text" class="form-control @error('company') is-invalid @enderror"
                                    id="company" name="company" value="{{ old('company') }}" required>
                                @error('company')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                        id="password" name="password" required
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                            <i class="fas fa-eye" id="eyeIcon"></i>
                                        </button>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback d-block w-100">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" required
                                        style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm"
                                            style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Register</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('recruiter.login') }}">Already have an account? Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('register_recruiter_scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggle1 = document.getElementById('togglePassword');
            const password1 = document.getElementById('password');
            const icon1 = document.getElementById('eyeIcon');

            toggle1.addEventListener('click', function() {
                const type = password1.getAttribute('type') === 'password' ? 'text' : 'password';
                password1.setAttribute('type', type);
                icon1.classList.toggle('fa-eye');
                icon1.classList.toggle('fa-eye-slash');
            });

            const toggle2 = document.getElementById('togglePasswordConfirm');
            const password2 = document.getElementById('password_confirmation');
            const icon2 = document.getElementById('eyeIconConfirm');

            toggle2.addEventListener('click', function() {
                const type = password2.getAttribute('type') === 'password' ? 'text' : 'password';
                password2.setAttribute('type', type);
                icon2.classList.toggle('fa-eye');
                icon2.classList.toggle('fa-eye-slash');
            });
        });
    </script>
@endpush
