<?php

namespace App\Helpers;

use App\Models\User;

trait AuthHelper
{
    /**
     * Handle verified recruiter logic.
     *
     * @param string $email
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function handleVerifiedRecruiter(string $email)
    {
        $user = User::where('email', $email)
            ->whereIn('role', ['recruiter', 'recruiter_assistant'])
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Recruiter account not found.',
            ])->withInput();
        }

        if ($user->status === 'banned') {
            return back()->withErrors([
                'email' => 'Your account is banned. Please contact support.',
            ])->withInput();
        }

        session([
            'recruiter_password_reset_allowed' => true,
            'recruiter_verified_email' => $email,
            'recruiter_reset_expires_at' => now()->addMinutes(10),
        ]);

        return redirect()->route('recruiter.password.reset.form')
            ->with('success', 'OTP verified. You can now reset your password.');
    }
}
