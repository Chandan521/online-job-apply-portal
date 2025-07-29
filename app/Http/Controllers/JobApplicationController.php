<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Job;
use App\Models\JobApplication;
use App\Notifications\NewJobApplicationNotification;

class JobApplicationController extends Controller
{
    public function showForm(Request $request, $jobId)
    {
        $step = $request->input('step', 'contact');
        $user = Auth::user();
        $job = \App\Models\Job::findOrFail($jobId);
        $userData = Session::get('job_apply_data', [
            'first_name' => $user ? explode(' ', $user->name)[0] : '',
            'last_name' => $user ? (explode(' ', $user->name)[1] ?? '') : '',
            'email' => $user ? $user->email : '',
            'phone' => $user ? $user->phone : '',
            'city' => $user ? (!empty($user->city) ? $user->city : (!empty($user->address) ? $user->address : '')) : '',
            'address' => $user ? ($user->address ?? '') : '',
        ]);
        $stepPercentage = $step === 'contact' ? 33 : ($step === 'resume' ? 66 : 100);
        $userResume = $user && $user->resume ? $user->resume : null;
        return view('apply.job_apply', compact('step', 'stepPercentage', 'userData', 'job', 'userResume'));
    }

    // User Job Application withdraw 
    public function withdraw(Request $request, $id)
    {
        $user = Auth::user();

        $application = JobApplication::where('id', $id)
            ->where('user_id', $user->id)
            ->first();
        if (!$application) {
            return back()->with('error', 'Application not found or access denied.');
        }

        if ($application->status === 'withdrawn') {
            return back()->with('info', 'You have already withdrawn this application.');
        }

        try {
            $application->status = 'withdrawn';
            // $application->withdrawn_at = now(); // Optional: add this column
            $application->save();

            return back()->with('success', 'You have successfully withdrawn your application.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to withdraw application. Please try again.');
        }
    }

    // User withdraw and Rejected Job Application Delete
    public function destroy($id)
    {
        $application = JobApplication::findOrFail($id);

        if (auth()->id() !== $application->user_id) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if (!in_array($application->status, ['withdrawn', 'rejected'])) {
            return redirect()->back()->with('error', 'Only rejected or withdrawn applications can be deleted.');
        }

        $application->delete();

        return redirect()->back()->with('success', 'Application deleted successfully.');
    }
}
