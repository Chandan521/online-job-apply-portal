<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\UserBlock;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\ApplicationMessage;
use App\Http\Controllers\Controller;
use App\Events\ApplicationMessageSent;
use App\Helpers\RecruiterHelper;

class RecruiterJobApplicationController extends Controller
{
    public function index(Request $request)
    {
        $recruiterId = RecruiterHelper::getRecruiterId();

        $blockedUserIds = UserBlock::where('from_user_id', $recruiterId)->pluck('to_user_id');

        $query = JobApplication::with(['user', 'job'])
            ->whereHas('job', function ($q) use ($recruiterId) {
                $q->where('recruiter_id', $recruiterId);
            })
            ->where('status', '!=', 'withdrawn')
            ->whereNotIn('user_id', $blockedUserIds);

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10)->appends($request->all());

        return view('recruiter.dashboard.job_application.index', compact('applications'));
    }

    public function show($id)
    {
        $application = JobApplication::with(['user', 'job', 'messages.sender'])->findOrFail($id);

        if ($application->job->recruiter_id !== RecruiterHelper::getRecruiterId()) {
            abort(403);
        }

        if ($application->status === 'submitted') {
            $application->status = 'under_review';
            $application->save();
        }

        foreach ($application->messages as $msg) {
            if (
                (isset($msg->sender) && isset($msg->sender->role) && $msg->sender->role !== 'recruiter')
                && !$msg->is_read
            ) {
                $msg->is_read = true;
                $msg->save();
            }
        }

        return view('recruiter.dashboard.job_application.show', compact('application'));
    }

    public function updateStatus(Request $request, $id)
    {
        $application = JobApplication::with('job')->findOrFail($id);

        if ($application->job->recruiter_id !== RecruiterHelper::getRecruiterId()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:under_review,shortlisted,interview,selected,rejected,hired'
        ]);

        $application->status = $request->status;
        $application->save();

        return back()->with('success', 'Application status updated.');
    }

    public function export(Request $request)
    {
        $recruiterId = RecruiterHelper::getRecruiterId();

        $query = JobApplication::with(['user', 'job'])
            ->whereHas('job', function ($q) use ($recruiterId) {
                $q->where('recruiter_id', $recruiterId);
            })
            ->where('status', '!=', 'withdrawn');

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->get();

        $filename = 'applications_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Candidate', 'Email', 'Phone', 'Job Title', 'Status', 'Applied On']);
            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->first_name . ' ' . $app->last_name,
                    $app->email,
                    $app->country_code . ' ' . $app->phone,
                    $app->job->title ?? '-',
                    $app->status,
                    $app->created_at->format('d-m-Y'),
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
