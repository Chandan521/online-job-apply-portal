<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JobApplication;
use App\Models\ApplicationMessage;
use Illuminate\Support\Facades\Log;
use App\Events\ApplicationMessageSent;

class MessageController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $applications = JobApplication::with(['job', 'messages.sender'])
            ->where('user_id', $user->id)
            ->get();
        return view('conversations.message', [
            'applications' => $applications,
            'selectedApplication' => null,
        ]);
    }

    public function show($applicationId)
    {
        $user = auth()->user();

        $selected = JobApplication::with(['job', 'messages.sender'])
            ->where('user_id', $user->id)
            ->findOrFail($applicationId);

        // Mark all unread recruiter messages as read
        $selected->messages()
            ->where('sender_id', '!=', $user->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $applications = JobApplication::with(['job', 'messages.sender'])
            ->where('user_id', $user->id)
            ->get();

        return view('conversations.message', [
            'applications' => $applications,
            'selectedApplication' => $selected,
        ]);
    }

    public function sendMessage(Request $request, $applicationId)
    {
        try {
            $request->validate([
                'message' => 'required_without:file|string|max:1000',
                'file' => 'required_without:message|file|mimes:jpg,jpeg,png,bmp,gif,svg,webp,pdf,doc,docx,txt|max:2048',
            ]);

            $application = JobApplication::findOrFail($applicationId);

            if ($application->user_id !== auth()->id()) {
                abort(403);
            }

            $message = new ApplicationMessage();
            $message->job_application_id = $application->id;
            $message->sender_id = auth()->id();
            $message->message = $request->input('message');

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('chat_files', 'public'); // stored in storage/app/public/chat_files
                $message->file_path = '/storage/' . $path; // full public path
            }

            $message->save();

            $message->load('sender');

            broadcast(new ApplicationMessageSent($message))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Message sent successfully',
                'data' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Message send failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
