<?php

namespace App\Http\Controllers\Recruiter;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\ApplicationMessage;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Events\ApplicationMessageSent;

class RecruiterMessageController extends Controller
{
    public function sendMessage(Request $request, $id)
    {
        try {
            $request->validate([
                'message' => 'required_without:file|string|max:1000',
                'file' => 'required_without:message|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,pdf,doc,docx,txt|max:2048',
            ]);

            $application = JobApplication::with('job', 'messages.sender')->findOrFail($id);

            if ($application->job->recruiter_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }



            $message = new ApplicationMessage();
            $message->job_application_id = $application->id;
            $message->sender_id = auth()->id();
            $message->message = $request->message;

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('chat_files', 'public');
                $message->file_path = '/storage/' . $path;
            }



            $message->save();

            $message->load('sender');

            broadcast(new ApplicationMessageSent($message));

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Message send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
