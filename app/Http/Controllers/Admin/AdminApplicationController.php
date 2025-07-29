<?php

namespace App\Http\Controllers\Admin;

use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with(['job.recruiter', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%$search%"))
                ->orWhereHas('job', fn($q) => $q->where('title', 'like', "%$search%"));
        }

        $applications = $query->latest()->paginate(10);

        // Chart data
        $chartData = JobApplication::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')->pluck('count', 'status')->toArray();

        return view('admin.job_application.index', compact('applications', 'chartData'));
    }
    public function job_view($id)
    {
        $job = Job::with(['recruiter', 'applications.user'])->findOrFail($id);

        return view('admin.job_application.view_job', compact('job'));
    }

    public function user_view($id)
    {
        $user = User::with(['jobApplications.job'])->findOrFail($id);

        return view('admin.job_application.view_user', compact('user'));
    }
    public function recruiters_view($id)
    {
        $recruiter = User::where('role', 'recruiter')->findOrFail($id);

        $jobs = Job::where('recruiter_id', $recruiter->id)
            ->latest()
            ->paginate(10);
        return view('admin.job_application.view_recruiter', compact('recruiter', 'jobs'));
    }
}
