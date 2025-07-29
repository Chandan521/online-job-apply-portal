<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\LoginLog;
use Illuminate\Support\Str;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;


class UserAuthController extends Controller
{
    protected function createLoginNotification(Request $request, $user, $type = 'success')
    {
        $ip = $request->ip();
        $agent = $request->header('User-Agent');

        // Check if this device (user-agent + IP) was used before
        $existing = DB::table('login_logs')
            ->where('user_id', $user->id)
            ->where('ip_address', $ip)
            ->where('user_agent', $agent)
            ->exists();

        // Only send notification if it's a new device
        if (!$existing && $type === 'success') {
            DB::table('notifications')->insert([
                'user_id' => $user->id,
                'title' => 'New Device Login',
                'message' => 'New login from an unrecognized device (IP: ' . $ip . ')',
                'is_read' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Optional: log every login attempt (for record-keeping)
        DB::table('login_logs')->insert([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }


    public function logout(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($user && in_array($user->role, ['recruiter', 'recruiter_assistant'])) {
            LoginLog::where('session_id', session()->getId())
                ->where('user_id', auth()->id())
                ->latest()
                ->first()
                ?->update([
                    'logout_at' => now(),
                    'duration_in_seconds' => now()->diffInSeconds(auth()->user()->last_login_at),
                ]);

            return redirect()->route('recruiter.login')->with('success', 'You have been signed out.');
        }

        if ($user && $user->role === 'admin') {
            LoginLog::where('session_id', session()->getId())
                ->where('user_id', auth()->id())
                ->latest()
                ->first()
                ?->update([
                    'logout_at' => now(),
                    'duration_in_seconds' => now()->diffInSeconds(auth()->user()->last_login_at),
                ]);

            return redirect()->route('admin.auth')->with('success', 'You have been signed out.');
        }

        if ($user && $user->role === 'job_seeker') {
            LoginLog::where('session_id', session()->getId())
                ->where('user_id', auth()->id())
                ->latest()
                ->first()
                ?->update([
                    'logout_at' => now(),
                    'duration_in_seconds' => now()->diffInSeconds(auth()->user()->last_login_at),
                ]);

            return redirect()->route('user_login')->with('success', 'You have been signed out.');
        }

        return redirect('/')->with('success', 'You have been signed out.');
    }
    // Recruiter Login Form
    public function showRecruiterLoginForm()
    {
        return view('auth.recruiter_login');
    }
    // Recruiter Register Form
    public function showRecruiterRegisterForm()
    {
        return view('auth.recruiter_register');
    }
    // Recruiter Login Backend
    public function recruiterLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ], $request->filled('remember'))) {

            $request->session()->regenerate();

            $user = Auth::user();

            if (!$user) {
                return back()->withErrors(['email' => 'Login failed, user not found.']);
            }

            if (!in_array($user->role, ['recruiter', 'recruiter_assistant'])) {
                Auth::guard('recruiter')->logout(); // ✅ fixed
                return back()->withErrors([
                    'email' => 'This is not a recruiter account.',
                ]);
            }

            // ✅ Check account status
            if ($user->status === 'banned') {
                Auth::guard('recruiter')->logout();
                return back()->withErrors([
                    'email' => 'Your account is banned.',
                ]);
            }

            if ($user->status === 'inactive') {
                Auth::guard('recruiter')->logout();
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ]);
            }

            if ($user->status !== 'active') {
                Auth::guard('recruiter')->logout();
                return back()->withErrors([
                    'email' => 'Your account status is not valid.',
                ]);
            }

            // ✅ Save login info
            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            LoginLog::create([
                'user_id' => $user->id,
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'session_id' => session()->getId(),
            ]);
            $user->save();

            // ✅ Optional: Log successful login
            $this->createLoginNotification($request, $user, 'success');

            return redirect()->route('hire')->with('success', 'Recruiter login successful!');
        }

        // Optional: Log failed attempt
        if ($user = User::where('email', $credentials['email'])->first()) {
            $this->createLoginNotification($request, $user, 'failed');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    // Recruiter Register Backend
    public function recruiterRegister(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'company' => 'required|string|max:255',
            'password' => 'required|confirmed|min:8',
        ]);
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'company' => $data['company'],
            'role' => 'recruiter',
            'password' => bcrypt($data['password']),
        ]);
        Auth::login($user);
        LoginLog::create([
            'user_id' => auth()->id(),
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
        return redirect()->route('recruiter_dashboard');
    }
    public function showRecruiterForgotPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $resend = $request->query('resend') == 1;

        if ($email) {
            $email = trim($email);
            $user = User::where('email', $email)->first();

            if (!$user || !in_array(strtolower(trim($user->role)), ['recruiter', 'recruiter_assistant'])) {
                return redirect()->route('recruiter.password.request')
                    ->withErrors(['email' => 'This is not a valid recruiter account.']);
            }

            // ✅ Check if mail is enabled
            $smtpEnabled = setting('mail_enabled', '1') === '1';
            // Generate OTP
            $otp = rand(100000, 999999);

            // Check if unexpired OTP already exists
            $existingOtp = \App\Models\Otp::where('email', $email)
                ->where('verified', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
            if (!$existingOtp || $resend) {
                $otp = rand(100000, 999999);
                \App\Models\Otp::create([
                    'email' => $email,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                    'verified' => false,
                ]);

                if ($smtpEnabled) {
                    Mail::to($email)->send(new \App\Mail\RecruiterPasswordResetOtpMail($otp));
                }
            } else {
                $otp = $existingOtp->otp;
            }

            $otpRecord = \App\Models\Otp::where('email', $email)
                ->where('otp', $otp)
                ->where('verified', false)
                ->latest()
                ->first();

            if (!$otpRecord || now()->gt($otpRecord->expires_at)) {
                return back()->withErrors(['otp' => 'OTP has expired. Please try again.']);
            }
            return view('auth.passwords.recruiter_reset', ['email' => $email])
                ->with('success', $smtpEnabled ? 'OTP sent to your email.' : 'OTP generated. Enter to continue.');
        }

        // Show form to enter email if no query provided
        return view('auth.passwords.recruiter_reset');
    }
    public function handleRecruiterForgotPassword(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = trim($request->input('email'));

        // Fetch user
        $user = User::where('email', $email)->first();

        if (
            !$user ||
            !in_array(strtolower(trim($user->role)), ['recruiter', 'recruiter_assistant'])
        ) {
            return back()->withErrors(['email' => 'This is not a recruiter account.'])->withInput();
        }

        $smtpEnabled = setting('mail_enabled', '1') === '1';


        return redirect()->route('recruiter.password.request', ['email' => $email])
            ->with('success', $smtpEnabled ? 'OTP sent to your email.' : 'OTP generated. Enter OTP to continue.');
    }
    public function verifyRecruiterOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = trim($request->input('email'));
        $otp = $request->input('otp');

        $isMailEnabled = setting('mail_enabled', '1') == '1';

        if ($isMailEnabled) {
            $otpRecord = \App\Models\Otp::where('email', $email)
                ->where('otp', $otp)
                ->where('verified', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
        } else {
            $otpRecord = $otp === '123456' ? true : false;
        }

        if ($otpRecord) {
            // 🔒 Check if recruiter is banned
            $user = \App\Models\User::where('email', $email)
                ->whereIn('role', ['recruiter', 'recruiter_assistant'])
                ->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Recruiter account not found.'])->withInput();
            }

            if ($user->status === 'banned') {
                return back()->withErrors(['email' => 'Your account is banned. Please contact customer care.'])->withInput();
            }

            // ✅ Mark OTP as verified (only if mail is enabled and OTP is a DB record)
            if ($isMailEnabled && $otpRecord instanceof \App\Models\Otp) {
                $otpRecord->update(['verified' => true]);
            }

            return redirect()->route('recruiter.password.reset.form', ['email' => $email])
                ->with('success', 'OTP verified. You can now reset your password.');
        }

        return back()->withErrors(['otp' => 'Invalid OTP.'])->withInput();
    }

    public function showRecruiterPasswordResetForm(Request $request)
    {
        $email = $request->email; // or use route/query param (see below)

        if (!$email || !\App\Models\Otp::where('email', $email)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->exists()) {
            return redirect()->route('recruiter.password.request')
                ->withErrors(['email' => 'Password reset session expired or OTP not verified.']);
        }

        return view('auth.passwords.recruiter-reset-password', ['email' => $email]);
    }

    public function updateRecruiterPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        $otp = \App\Models\Otp::where('email', trim($request->email))
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return redirect()->route('recruiter.password.request')
                ->withErrors(['email' => 'Reset session expired or OTP invalid. Please try again.']);
        }


        $user = User::where('email', trim($request->input('email')))->first();

        if (
            !$user ||
            !in_array(strtolower(trim($user->role)), ['recruiter', 'recruiter_assistant'])
        ) {
            return back()->withErrors(['email' => 'Invalid recruiter email.']);
        }


        // ✅ Prevent resetting to the same password
        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'New password cannot be the same as the old password.']);
        }

        // ✅ Save new password
        $user->password = bcrypt($request->password);
        $user->save();

        $otp->delete();
        // ✅ Create the Password Changed notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Password Changed',
            'message' => 'Your account password has been successfully changed.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('recruiter.login')->with('success', 'Password reset successful. Please log in.');
    }
    // ----------------------------------------------------------------------
    // Job Seeker Auth Start 
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if ($user->role !== 'job_seeker') {
                Auth::logout();
                return back()->with('error', 'You are not allowed to login from here.');
            }
            if ($user->status === 'banned') {
                Auth::logout();
                return back()->with('error', 'Your account is banned.');
            }

            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->with('error', 'Your account is inactive.');
            }

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->with('error', 'Your account status is not valid.');
            }

            $request->session()->regenerate();

            $user->last_login_at = now();
            $user->last_login_ip = $request->ip();
            LoginLog::create([
                'user_id' => auth()->id(),
                'login_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
                'session_id' => session()->getId(),
            ]);
            $user->save();

            // 🔔 Trigger login notification
            $this->createLoginNotification($request, $user);

            return redirect()->intended('/')->with('success', 'Login successful!');
        }

        return back()->with('error', 'Invalid credentials.');
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'job_seeker',
        ]);
        Auth::login($user);
        LoginLog::create([
            'user_id' => auth()->id(),
            'login_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'session_id' => session()->getId(),
        ]);
        return redirect('/')->with('success', 'Registration successful! Welcome to HireMe.');
    }
    public function showJobSeekerForgotPasswordForm(Request $request)
    {
        $email = $request->query('email');
        $resend = $request->query('resend') == 1;

        if ($email) {
            $email = trim($email);
            $user = User::where('email', $email)->first();

            if (!$user || strtolower($user->role) !== 'job_seeker') {
                return redirect()->route('jobseeker.password.request')
                    ->withErrors(['email' => 'This is not a valid job seeker account.']);
            }

            // ✅ Check if mail is enabled
            $smtpEnabled = setting('mail_enabled', '1') === '1';

            // ✅ Generate OTP
            $otp = rand(100000, 999999);
            $existingOtp = \App\Models\Otp::where('email', $email)
                ->where('verified', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
            if (!$existingOtp || $resend) {
                $otp = rand(100000, 999999);
                \App\Models\Otp::create([
                    'email' => $email,
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(10),
                    'verified' => false,
                ]);

                if ($smtpEnabled) {
                    Mail::to($email)->send(new \App\Mail\UserPasswordResetOtpMail($otp));
                }
            } else {
                $otp = $existingOtp->otp;
            }

            $otpRecord = \App\Models\Otp::where('email', $email)
                ->where('otp', $otp)
                ->where('verified', false)
                ->latest()
                ->first();
            if (!$otpRecord || now()->gt($otpRecord->expires_at)) {
                return back()->withErrors(['otp' => 'OTP has expired. Please try again.']);
            }
            return view('auth.passwords.job_seeker_reset', ['email' => $email])
                ->with('success', $smtpEnabled ? 'OTP sent to your email.' : 'OTP generated. Enter to continue.');
        }

        // Show form to enter email if no query provided
        return view('auth.passwords.job_seeker_reset');
    }

    public function handleJobSeekerForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = trim($request->email);
        $user = User::where('email', $email)->where('role', 'job_seeker')->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No job seeker account found.'])->withInput();
        }

        $smtpEnabled = setting('mail_enabled', '1') === '1';

        return redirect()->route('jobseeker.password.request', ['email' => $email])
            ->with('success', $smtpEnabled ? 'OTP sent to your email.' : 'OTP generated. Enter OTP to continue.');
    }
    public function verifyJobSeekerOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric|digits:6',
        ]);

        $email = trim($request->email);
        $otp = $request->otp;
        $isMailEnabled = setting('mail_enabled', '1') == '1';

        if ($isMailEnabled) {
            $otpRecord = \App\Models\Otp::where('email', $email)
                ->where('otp', $otp)
                ->where('verified', false)
                ->where('expires_at', '>', now())
                ->latest()
                ->first();
        } else {
            $otpRecord = $otp === '123456' ? true : false;
        }

        if ($otpRecord) {
            $user = User::where('email', $email)->where('role', 'job_seeker')->first();

            if (!$user) {
                return back()->withErrors(['email' => 'Job seeker account not found.'])->withInput();
            }

            if ($user->status === 'banned') {
                return back()->withErrors(['email' => 'Your account is banned.'])->withInput();
            }

            // ✅ Mark OTP as verified (only if mail is enabled and OTP is a DB record)
            if ($isMailEnabled && $otpRecord instanceof \App\Models\Otp) {
                $otpRecord->update(['verified' => true]);
            }

            return redirect()->route('jobseeker.password.reset.form', ['email' => $email])
                ->with('success', 'OTP verified. You can now reset your password.');
        }

        return back()->withErrors(['otp' => 'Invalid OTP.'])->withInput();
    }
    public function showJobSeekerPasswordResetForm(Request $request)
    {
        $email = $request->email; // or use route/query param (see below)

        if (!$email || !\App\Models\Otp::where('email', $email)
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->exists()) {
            return redirect()->route('jobseeker.password.request')
                ->withErrors(['email' => 'Reset session expired.']);
        }

        return view('auth.passwords.job_seeker-reset-password', ['email' => $email]);
    }
    public function updateJobSeekerPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6',
        ]);
        $otp = \App\Models\Otp::where('email', trim($request->email))
            ->where('verified', true)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$otp) {
            return redirect()->route('jobseeker.password.request')->withErrors(['email' => 'Reset session expired or OTP invalid. Please try again.']);
        }

        $user = User::where('email', trim($request->input('email')))->where('role', 'job_seeker')->first();

        if (!$user || !$user->role === 'job_seeker') {
            return back()->withErrors(['email' => 'Invalid job seeker email.']);
        }

        if (Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'New password cannot be same as old one.']);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        $otp->delete();

        // ✅ Create the Password Changed notification
        Notification::create([
            'user_id' => $user->id,
            'title' => 'Password Changed',
            'message' => 'Your account password has been successfully changed.',
            'is_read' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return redirect()->route('user_login')->with('success', 'Password updated. Please log in.');
    }
}
