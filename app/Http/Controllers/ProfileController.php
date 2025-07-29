<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        /** @var \App\Models\User $user */
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'experience' => 'nullable|string|max:255',
            'linkedin_url' => 'nullable|url',
            'about_me' => 'nullable|string',
            'skills' => 'nullable|string',
            'resume' => 'nullable|file|mimes:pdf|max:2048',
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        $user->fill($request->except(['resume', 'profile_photo']));
        if ($request->hasFile('resume')) {
            $user->resume = $request->file('resume')->store('resumes', 'public');
        }
        if ($request->hasFile('profile_photo')) {
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        $user->save();
        return redirect()->back()->with('success', 'Profile updated successfully!');
    }



    // User Update password
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:5|confirmed', // requires new_password_confirmation
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->withInput();
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully.');
    }
    // Backup For Recruiter 
    // public function updatePassword(Request $request)
    // {
    //     $request->validate([
    //         'current_password' => 'required',
    //         'new_password' => 'required|confirmed|min:8',
    //     ]);
    //     $user = Auth::user();
    //     if (!Hash::check($request->current_password, $user->password)) {
    //         return back()->withErrors(['current_password' => 'Current password is incorrect.']);
    //     }
    //     $user->password = bcrypt($request->new_password);
    //     $user->save();
    //     return back()->with('success', 'Password updated successfully.');
    // }

    // User Profile Controller 
    public function settings(Request $request)
    {
        $tab = $request->query('tab', 'profile');
        $user = Auth::user();
        $reviews = Review::with('job') // Eager load job title/company
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
        return view('settings', compact('tab', 'user', 'reviews'));
    }
    // Update user profile info
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:255',
            'profile_photo' => 'nullable|image|max:2048',
        ]);
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }
        $user->update($data);
        return back()->with('success', 'Profile updated successfully.');
    }
    // Upload resume
    public function uploadResume(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'resume' => 'required|file|mimes:pdf|max:2048',
        ]);
        $resumePath = $request->file('resume')->store('resumes', 'public');
        $user->resume = $resumePath;
        $user->save();
        return back()->with('success', 'Resume uploaded successfully.');
    }
    // Delete resume
    public function deleteResume(Request $request)
    {
        $user = Auth::user();
        if ($user->resume) {
            Storage::disk('public')->delete($user->resume);
            $user->resume = null;
            $user->save();
        }
        return back()->with('success', 'Resume deleted.');
    }
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        Auth::logout();
        $user->delete();
        return redirect('/')->with('success', 'Account deleted.');
    }
    public function deactivate(Request $request)
    {
        $user = Auth::user();

        // You can either soft delete or mark as deactivated
        $user->status = 'inactive'; // make sure 'status' column exists in `users` table
        $user->save();
        Auth::logout();
        return redirect('/')->with('message', 'Your account has been deactivated.');
    }
}
