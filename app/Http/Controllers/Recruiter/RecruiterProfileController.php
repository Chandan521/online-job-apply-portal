<?php

namespace App\Http\Controllers\Recruiter;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RecruiterProfileController extends Controller
{
    // 🔐 Recruiter Account Settings Page
    public function recruiterAccountSettings()
    {
        $userId = Auth::id();

        // Get active sessions (device management)
        $devices = DB::table('sessions')
            ->where('user_id', $userId)
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                return (object)[
                    'id' => $session->id,
                    'ip_address' => $session->ip_address,
                    'user_agent' => $session->user_agent,
                    'login_at' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'is_current_device' => $session->id === session()->getId(),
                    'browser' => get_browser_from_agent($session->user_agent),
                    'os' => get_os_from_agent($session->user_agent),
                ];
            });

        // Fetch latest unread notifications
        $notifications = DB::table('notifications')
            ->where('user_id', $userId)
            ->where('is_read', 0)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function ($note) {
                $note->created_at = Carbon::parse($note->created_at);
                return $note;
            });

        return view('recruiter.account_settings', compact('devices', 'notifications'));
    }

    // 🔄 Update Recruiter Profile
    public function recruiter_update_profile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'nullable|in:recruiter,jobseeker',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'about_me' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        foreach ($data as $key => $value) {
            if (!in_array($key, ['profile_photo', 'role'])) {
                $user->$key = $value;
            }
        }

        // ✅ Map role input to DB format
        $role = $request->input('role');
        if ($role === 'jobseeker') {
            $user->role = 'job_seeker';
        } elseif ($role === 'recruiter') {
            $user->role = 'recruiter';
        }

        // ✅ Handle image upload
        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo && Storage::disk('public')->exists($user->profile_photo)) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->save();

        // If role changed → force logout and re-login
        if ($user->wasChanged('role')) {
            Auth::logout();
            return redirect()->route('recruiter.login')->with('message', 'Role changed. Please log in again.');
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    // 🔒 Update Recruiter Password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $user = Auth::user(); // ✅ default guard

        if (!$user) {
            return back()->withErrors(['error' => 'Unauthorized user.'])->withInput();
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->password = bcrypt($request->password);
        $user->save();

        // Optional: re-login to refresh session (using default guard)
        Auth::login($user);

        return back()->with('success', 'Password updated successfully.');
    }
}
