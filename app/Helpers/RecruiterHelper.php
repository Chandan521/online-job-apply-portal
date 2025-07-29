<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RecruiterHelper
{
    public static function getRecruiterId(): int
    {
        $user = Auth::user();
        return ($user && $user->role === 'recruiter_assistant' && $user->parent_id)
            ? $user->parent_id
            : $user->id;
    }
}
