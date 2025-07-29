<?php

namespace App\Http\Controllers\Recruiter;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class RecruiterDeviceController extends Controller
{
    public function logout($deviceId)
    {
        // Assuming you're using session tracking in DB (like Jetstream or custom)
        DB::table('sessions')->where('id', $deviceId)->delete();
        
        return back()->with('status', 'Device signed out successfully.');
    }
}
