<?php

namespace App\Http\Controllers\Recruiter;

use App\Models\Setting;
use App\Mail\InterviewMail;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\ScheduledInterview;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Notifications\InterviewRevokedNotification;
use App\Notifications\InterviewUpdatedNotification;
use App\Notifications\InterviewScheduledNotification;

class RecruiterInterviewController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $mainRecruiterId = $user->role === 'recruiter_assistant' ? $user->parent_id : $user->id;

        $interviews = ScheduledInterview::with(['application.user', 'job'])
            ->where('recruiter_id', $mainRecruiterId)
            ->latest()
            ->get();

        return view('recruiter.interviews.index', compact('interviews'));
    }

    public function schedule(Request $request)
    {
        $request->validate([
            'job_application_id' => 'required|exists:job_applications,id',
            'job_id' => 'required|exists:jobs,id',
            'interview_datetime' => 'required|date|after:now',
            'mode' => 'required|string|in:online,in-person,phone',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $application = JobApplication::findOrFail($request->job_application_id);

        $currentUser = auth()->user();
        $mainRecruiterId = $currentUser->role === 'recruiter_assistant' ? $currentUser->parent_id : $currentUser->id;

        if ($application->job->recruiter_id !== $mainRecruiterId) {
            abort(403);
        }

        $alreadyScheduled = ScheduledInterview::where('job_application_id', $application->id)
            ->whereIn('status', ['scheduled', 'rescheduled'])
            ->exists();

        if ($alreadyScheduled) {
            return back()->with('error', 'Interview already scheduled for this application.');
        }

        $application->status = 'interview';
        $application->save();

        $interview = ScheduledInterview::create([
            'job_application_id' => $application->id,
            'job_id' => $request->job_id,
            'recruiter_id' => $mainRecruiterId,
            'created_by' => $currentUser->id,
            'interview_datetime' => $request->interview_datetime,
            'mode' => $request->mode,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        // ✅ Notify user
        $this->notifyUser($application, new InterviewScheduledNotification($interview));

        return back()->with('success', 'Interview scheduled successfully.');
    }

    public function update(Request $request, ScheduledInterview $interview)
    {
        $request->validate([
            'interview_datetime' => 'required|date|after:now',
            'mode' => 'required|string|in:online,in-person,phone',
            'location' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $this->authorizeRecruiter($interview);

        $interview->update([
            'interview_datetime' => $request->interview_datetime,
            'mode' => $request->mode,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        $this->notifyUser($interview->application, new InterviewUpdatedNotification($interview));

        return back()->with('success', 'Interview rescheduled successfully.');
    }

    public function destroy(ScheduledInterview $interview)
    {
        $this->authorizeRecruiter($interview);

        $interview->application->update(['status' => 'under_review']);

        $interview->delete();

        $this->notifyUser($interview->application, new InterviewRevokedNotification($interview));

        return back()->with('success', 'Interview revoked.');
    }

    private function authorizeRecruiter($interview)
    {
        $user = auth()->user();
        $mainId = $user->role === 'recruiter_assistant' ? $user->parent_id : $user->id;

        if ($interview->recruiter_id !== $mainId) {
            abort(403);
        }
    }

    private function notifyUser(JobApplication $application, $notification)
    {
        $user = $application->user;

        // ✅ Insert into your custom notifications table
        Notification::create([
            'user_id' => $user->id,
            'title' => $notification->title,
            'message' => $notification->message,
            'is_read' => 0,
        ]);

        // ✅ Optional Mail Notification
        $settingsMailEnabled = Setting::where('key', 'mail_enabled')->value('value');

        if ($settingsMailEnabled && config('mail.mailers.smtp.transport') !== null) {
            try {
                Mail::to($user->email)->send(new InterviewMail($notification->title, $notification->message));
            } catch (\Exception $e) {
                Log::error('Mail not sent: ' . $e->getMessage());
            }
        }
    }

    public function cancelInterview($id)
    {
        $interview = ScheduledInterview::findOrFail($id);
        $this->authorizeRecruiter($interview);

        $interview->status = 'cancelled';
        $interview->cancelled_by = auth()->user()->role; // 'recruiter' or 'job_seeker'
        $interview->action_by = auth()->user()->role;
        $interview->save();

        // Notify
        $this->notifyUser($interview->application, new InterviewRevokedNotification($interview));

        return redirect()->back()->with('success', 'Interview cancelled successfully.');
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
        $this->authorizeRecruiter($interview);

        $interview->interview_datetime = $request->interview_datetime;
        $interview->mode = $request->mode;
        $interview->location = $request->location;
        $interview->notes = $request->notes;
        $interview->status = 'rescheduled';
        $interview->rescheduled_by = auth()->user()->role; // 'recruiter'
        $interview->action_by = auth()->user()->role;
        $interview->save();

        // Notify
        $this->notifyUser($interview->application, new InterviewUpdatedNotification($interview));

        return redirect()->back()->with('success', 'Interview rescheduled successfully.');
    }
}
