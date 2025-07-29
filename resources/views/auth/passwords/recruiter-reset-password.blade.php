@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h4 class="mb-4 text-center">Reset Password</h4>

                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form method="POST" action="{{ route('recruiter.password.reset.update') }}">
                            @csrf

                            <input type="hidden" name="email" value="{{ $email }}">

                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    name="password" id="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control"
                                    name="password_confirmation" id="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                        </form>

                        <div class="mt-3 text-center">
                            <a href="{{ route('recruiter.login') }}">Back to Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
