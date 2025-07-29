<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\Job;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RecruiterDashboardController extends Controller
{
    // Recruiter: Dashboard with widgets
    public function recruiterDashboard()
    {
        $user = Auth::user();

        // Recruiter or assistant
        $recruiterId = $user->role === 'recruiter_assistant' && $user->parent_id
            ? $user->parent_id
            : $user->id;

        $jobs = Job::where('recruiter_id', $recruiterId);
        $jobIds = $jobs->pluck('id');

        // Stats
        $jobsCount = $jobs->where('status', 'active')->count();
        $applicantsCount = JobApplication::whereIn('job_id', $jobIds)->count();
        $interviewCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'interview')->count();
        $topCandidatesCount = JobApplication::whereIn('job_id', $jobIds)->where('status', 'shortlisted')->count();

        // Activities
        $recentActivities = collect();

        // Recent job postings
        $recentJobs = $jobs->latest()->take(2)->get();
        foreach ($recentJobs as $job) {
            $recentActivities->push([
                'icon' => 'bi-briefcase',
                'title' => 'New Job Posted',
                'description' => $job->title,
                'time' => $job->created_at->diffForHumans(),
            ]);
        }

        // New applications
        $recentApplicants = JobApplication::with('job', 'user')
            ->whereIn('job_id', $jobIds)
            ->latest()
            ->take(2)
            ->get();
        foreach ($recentApplicants as $app) {
            $recentActivities->push([
                'icon' => 'bi-person-plus',
                'title' => 'New Candidate',
                'description' => ucwords($app->user->name ?? 'Unknown') . ' applied for ' . ($app->job->title ?? '-'),
                'time' => $app->created_at->diffForHumans(),
            ]);
        }

        // Interviews scheduled
        $upcomingInterviews = JobApplication::with('job', 'user')
            ->whereIn('job_id', $jobIds)
            ->where('status', 'interview')
            ->orderBy('updated_at', 'desc')
            ->take(1)
            ->get();
        foreach ($upcomingInterviews as $interview) {
            $recentActivities->push([
                'icon' => 'bi-calendar2-check',
                'title' => 'Interview Scheduled',
                'description' => 'Interview with ' . ucwords($interview->user->name ?? 'Candidate') . ' for ' . ($interview->job->title ?? '-'),
                'time' => $interview->updated_at->diffForHumans(),
            ]);
        }

        // Placeholder: document upload and messages can be added similarly
        $recentActivities->push([
            'icon' => 'bi-file-earmark-text',
            'title' => 'Document Uploaded',
            'description' => 'John Doe uploaded resume for review',
            'time' => '4 hours ago',
        ]);
        $recentActivities->push([
            'icon' => 'bi-chat-dots',
            'title' => 'New Message',
            'description' => 'Robert Taylor sent a new message',
            'time' => '6 hours ago',
        ]);

        return view('recruiter.dashboard.dashboard', [
            'user' => $user,
            'jobsCount' => $jobsCount,
            'applicantsCount' => $applicantsCount,
            'interviewCount' => $interviewCount,
            'topCandidatesCount' => $topCandidatesCount,
            'recentActivities' => $recentActivities,
        ]);
    }
}
