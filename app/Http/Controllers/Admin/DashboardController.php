<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Helpers\SiteTrafficHelper;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function getSiteTraffic(Request $request)
    {
        $days = $request->get('days', 7);
        $traffic = SiteTrafficHelper::getSiteTrafficData($days);
        return response()->json($traffic);
    }


    public function dashboard()
    {

        $activeJobs = Job::where('deadline', '>=', Carbon::now())->count();
        $job_application = JobApplication::all()->count();
        $activeCandidate = User::where('role', 'job_seeker')->count();
        $activeCompany = User::where('role', 'recruiter')->count();
        $recentApplications = JobApplication::with('job','user')
        ->latest()
        ->take(5)
        ->get();
        $traffic = SiteTrafficHelper::getSiteTrafficData(7);
        return view('admin.dashboard.dashboard', [
            'activeJobs' => $activeJobs,
            'job_application' => $job_application,
            'activeCandidate' => $activeCandidate,
            'activeCompany' => $activeCompany,
            'days' => $traffic['labels'],
            'visits' => $traffic['visits'],
            'recentApplications' => $recentApplications,
        ]);
    }
    public function recent_application()
    {
        $applications = JobApplication::with(['job', 'user'])->latest()->paginate(20);
        return view('admin.dashboard.recent_application', compact('applications'));
    }
}
