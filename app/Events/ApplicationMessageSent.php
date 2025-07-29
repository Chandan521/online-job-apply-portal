<?php

namespace App\Events;

use App\Models\ApplicationMessage;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ApplicationMessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct(ApplicationMessage $message)
    {
        $this->message = $message->load('sender');
    }

    public function broadcastOn()
    {
        return new PrivateChannel('job-application.' . $this->message->job_application_id);
    }

    public function broadcastAs()
    {
        return 'ApplicationMessageSent';
    }

    public function broadcastWith()
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'message' => $this->message->message,
                // FIX: Output the file_path as saved (already has /storage/...)
                'file_path' => $this->message->file_path,
                'created_at' => $this->message->created_at->toDateTimeString(),
                'sender' => [
                    'id' => $this->message->sender->id,
                    'role' => $this->message->sender->role,
                    'name' => $this->message->sender->name,
                ],
            ]
        ];
    }
}