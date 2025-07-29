<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('hasAccess')) {
    function hasAccess($permission)
    {
        $user = Auth::user();

        if (!$user) return false;

        // ✅ Main recruiter (optional logic)
        if ($user->role === 'recruiter' && ($user->is_main_account ?? true)) {
            return true;
        }

        // ✅ Automatically cast to array if using model cast
        $permissions = $user->permissions ?? [];

        // Fallback decode in case it's still a JSON string
        if (!is_array($permissions)) {
            $permissions = json_decode($permissions, true) ?? [];
        }

        return in_array($permission, $permissions);
    }
}
