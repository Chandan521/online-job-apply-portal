<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ScheduledInterview;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    public function myScheduledInterviews()
    {
        $userId = auth()->id();

        $interviews = ScheduledInterview::whereHas('jobApplication', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->with(['job', 'recruiter', 'jobApplication'])
            ->orderBy('interview_datetime', 'asc')
            ->get();

        return view('interviews.index', compact('interviews'));
    }
    public function showRescheduleForm($id)
    {
        $interview = ScheduledInterview::findOrFail($id);
        if ($interview->jobApplication->user_id !== auth()->id()) {
            abort(403);
        }
        return view('interviews.reschedule', compact('interview'));
    }

    public function rescheduleInterview(Request $request, $id)
    {
        $request->validate([
            'interview_datetime' => 'required|date|after:now',
            'mode' => 'required|string|in:online,in-person,phone',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);
        $interview = ScheduledInterview::findOrFail($id);

        if ($interview->jobApplication->user_id !== auth()->id()) {
            abort(403);
        }

        $interview->interview_datetime = $request->interview_datetime;
        $interview->mode = $request->mode;
        $interview->location = $request->location;
        $interview->notes = $request->notes;
        $interview->status = 'rescheduled';
        $interview->rescheduled_by = 'job_seeker';
        $interview->action_by = 'job_seeker';
        $interview->save();

        // Notify recruiter
        // $this->notifyRecruiter($interview); // You should implement similar to recruiter->notifyUser

        return redirect()->back()->with('success', 'Interview rescheduled successfully.');
    }


    public function cancelInterview($id)
    {
        $interview = ScheduledInterview::findOrFail($id);

        if ($interview->jobApplication->user_id !== auth()->id()) {
            abort(403);
        }

        $interview->status = 'cancelled';
        $interview->cancelled_by = 'job_seeker';
        $interview->action_by = 'job_seeker';
        $interview->save();

        // Notify recruiter
        // $this->notifyRecruiter($interview);

        return redirect()->back()->with('success', 'Interview cancelled successfully.');
    }
}
