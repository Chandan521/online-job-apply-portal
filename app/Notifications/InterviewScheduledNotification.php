<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class InterviewScheduledNotification extends Notification
{
    public $title;
    public $message;

    public function __construct($interview)
    {
        $jobTitle = $interview->job->title ?? 'Job';
        $datetime = $interview->interview_datetime->format('d M Y h:i A');
        $this->title = 'Interview Scheduled';
        $this->message = "Your interview for <strong>{$jobTitle}</strong> has been scheduled on <strong>{$datetime}</strong> via <strong>{$interview->mode}</strong>.";
    }
}
