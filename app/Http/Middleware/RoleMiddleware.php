<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Not logged in: redirect to role-specific login
            if (in_array('recruiter', $roles)) {
                return redirect()->route('recruiter.login')->with('error', 'You must be logged in as a recruiter.');
            }

            if (in_array('job_seeker', $roles)) {
                return redirect()->route('user_login')->with('error', 'You must be logged in as a job seeker.');
            }

            if (in_array('admin', $roles)) {
                return redirect()->route('admin.login')->with('error', 'You must be logged in as an admin.');
            }

            return redirect('/')->with('error', 'You must be logged in.');
        }

        $user = Auth::user();

        // ✅ Expand role aliases (recruiter includes recruiter_assistant)
        $expandedRoles = collect($roles)->flatMap(function ($role) {
            return match ($role) {
                'recruiter' => ['recruiter', 'recruiter_assistant'],
                default => [$role],
            };
        })->unique()->toArray();

        // ✅ Check if the user's role is allowed
        if (!in_array($user->role, $expandedRoles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
    
}
