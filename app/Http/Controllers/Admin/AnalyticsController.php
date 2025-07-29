<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Job;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $startDate = $start ? Carbon::parse($start)->startOfDay() : null;
        $endDate = $end ? Carbon::parse($end)->endOfDay() : null;

        $jobsQuery = Job::query();
        $applicationsQuery = JobApplication::query();

        if ($startDate && $endDate) {
            $jobsQuery->whereBetween('created_at', [$startDate, $endDate]);
            $applicationsQuery->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totaljob_seeker = User::where('role', 'job_seeker')->count();
        $totalrecruiter = User::where('role', 'recruiter')->count();
        $total_jobs = $jobsQuery->count();

        $todayJobs = Job::whereDate('created_at', Carbon::today())->count();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $thisWeekJobs = Job::whereBetween('created_at', [$weekStart, $weekEnd])->count();

        $todayApplications = JobApplication::whereDate('created_at', Carbon::today())->count();
        $totalApplications = $applicationsQuery->count();
        $avgApplicationsPerJob = $total_jobs > 0 ? round($totalApplications / $total_jobs, 2) : 0;

        $topActiveJobSeekers = User::where('role', 'job_seeker')
            ->join('job_applications', 'users.id', '=', 'job_applications.user_id')
            ->select('users.id', 'users.name', DB::raw('COUNT(job_applications.id) as applications_count'))
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('applications_count')
            ->limit(4)
            ->get();

        $roleCounts = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->pluck('count', 'role');

        $registrationTrend = User::whereDate('created_at', '>=', now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date');

        $jobsByCategory = Job::select('industry', DB::raw('count(*) as count'))
            ->groupBy('industry')
            ->pluck('count', 'industry');

        $jobsByStatus = [
            'Active' => Job::where('deadline', '>=', Carbon::today())->count(),
            'Expired' => Job::where('deadline', '<', Carbon::today())->count(),
        ];

        $topRecruiters = Job::join('users', 'jobs.recruiter_id', '=', 'users.id')
            ->select('users.name as recruiter_name', DB::raw('count(jobs.id) as job_count'))
            ->groupBy('recruiter_name')
            ->orderByDesc('job_count')
            ->limit(5)
            ->get();

        $applicationStatusCounts = JobApplication::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $topAppliedJobs = JobApplication::join('jobs', 'job_applications.job_id', '=', 'jobs.id')
            ->select('jobs.title', DB::raw('count(job_applications.id) as application_count'))
            ->groupBy('jobs.title')
            ->orderByDesc('application_count')
            ->limit(5)
            ->get();

        $topKeywords = DB::table('search_logs')
            ->select('keyword', DB::raw('COUNT(*) as total'))
            ->groupBy('keyword')
            ->orderByDesc('total')
            ->limit(5)
            ->pluck('keyword');

        $mostViewedJobs = Job::orderByDesc('views')
            ->limit(5)
            ->get(['title', 'views']);

        $mostVisitedRecruiters = User::where('role', 'recruiter')
            ->orderByDesc('profile_visits')
            ->limit(5)
            ->get(['name', 'profile_visits']);

        $avgDuration = DB::table('login_logs')->avg('duration_in_seconds');
        $avgTimeInMinutes = $avgDuration ? round($avgDuration / 60, 1) : 0;

        $failedJobsCount = DB::table('failed_jobs')->count();
        $emailFailures = DB::table('email_logs')->where('status', 'failed')->count();

        $loginLogs = DB::table('login_logs')->pluck('user_agent');
        $deviceCounts = ['Desktop' => 0, 'Mobile' => 0, 'Tablet' => 0];

        foreach ($loginLogs as $agent) {
            $agent = strtolower($agent);
            if (str_contains($agent, 'mobile') && !str_contains($agent, 'tablet')) {
                $deviceCounts['Mobile']++;
            } elseif (str_contains($agent, 'tablet')) {
                $deviceCounts['Tablet']++;
            } else {
                $deviceCounts['Desktop']++;
            }
        }

        $totalDevices = array_sum($deviceCounts);
        $devicePercents = [];
        foreach ($deviceCounts as $type => $count) {
            $devicePercents[$type] = $totalDevices ? round(($count / $totalDevices) * 100) : 0;
        }

        return view('admin.analytics.index', compact(
            'totaljob_seeker',
            'totalrecruiter',
            'total_jobs',
            'todayJobs',
            'thisWeekJobs',
            'todayApplications',
            'totalApplications',
            'avgApplicationsPerJob',
            'topActiveJobSeekers',
            'roleCounts',
            'registrationTrend',
            'jobsByCategory',
            'jobsByStatus',
            'topRecruiters',
            'applicationStatusCounts',
            'topAppliedJobs',
            'topKeywords',
            'mostViewedJobs',
            'mostVisitedRecruiters',
            'avgTimeInMinutes',
            'failedJobsCount',
            'emailFailures',
            'devicePercents',
            'start',
            'end'
        ));
    }

    public function exportCSV(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');

        $startDate = $start ? Carbon::parse($start)->startOfDay() : null;
        $endDate = $end ? Carbon::parse($end)->endOfDay() : null;

        $query = Job::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $jobs = $query->withCount('applications')->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="job_analytics.csv"',
        ];

        $columns = ['Job Title', 'Company', 'Created At', 'Deadline', 'Applications'];

        $callback = function () use ($jobs, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($jobs as $job) {
                fputcsv($file, [
                    $job->title,
                    $job->company,
                    $job->created_at->format('Y-m-d'),
                    $job->deadline ? $job->deadline->format('Y-m-d') : 'N/A',
                    $job->applications_count ?? 0,
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
}
