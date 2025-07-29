@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="mb-4 text-center">User Forgot Password</h4>

                    <form method="POST"
                        action="{{ empty($email) ? route('jobseeker.password.send_otp') : route('jobseeker.password.verify_otp') }}">
                        @csrf

                        {{-- ✅ If no email, show email input --}}
                        @if (empty($email))
                            <div class="mb-3 d-flex gap-2">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required placeholder="Enter your email">
                                <button type="submit" class="btn btn-outline-primary">Send OTP</button>
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        @else
                            {{-- ✅ Masked email display --}}
                            @php
                                function maskEmail($email) {
                                    $parts = explode('@', $email);
                                    $username = $parts[0];
                                    $domain = $parts[1];
                                    return substr($username, 0, 1)
                                        . str_repeat('*', strlen($username) - 2)
                                        . substr($username, -1) . '@' . $domain;
                                }
                            @endphp

                            <div class="alert alert-success text-center">
                                OTP has been sent to <strong>{{ maskEmail($email) }}</strong>
                            </div>

                            {{-- ✅ Hidden email input --}}
                            <input type="hidden" name="email" value="{{ $email }}">

                            {{-- ✅ OTP input --}}
                            <div class="mb-3">
                                <label for="otp" class="form-label">Enter OTP</label>
                                <input type="text" class="form-control @error('otp') is-invalid @enderror"
                                    name="otp" id="otp" required>
                                @error('otp')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-2">Submit OTP</button>

                            {{-- ✅ Resend OTP --}}
                            <div class="text-center">
                                <button type="button" id="resendBtn" class="btn btn-link p-0" disabled>
                                    Resend OTP (<span id="countdown">60</span>s)
                                </button>
                                <a href="{{ route('jobseeker.password.request', ['email' => $email, 'resend' => 1]) }}"
                                    id="resendLink" class="d-none">Real Resend</a>
                            </div>
                        @endif
                    </form>

                    <div class="mt-3 text-center">
                        <a href="{{ route('user_login') }}">Back to Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('reset_password_script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const countdownSpan = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        const resendLink = document.getElementById('resendLink');
        let countdown = 60;

        const interval = setInterval(() => {
            countdown--;
            countdownSpan.textContent = countdown;

            if (countdown <= 0) {
                clearInterval(interval);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend OTP';
            }
        }, 1000);

        resendBtn.addEventListener('click', function () {
            resendLink.click();
        });
    });
</script>
@endsection
