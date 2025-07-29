<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class InterviewUpdatedNotification extends Notification
{
    public $title;
    public $message;

    public function __construct($interview)
    {
        $jobTitle = $interview->job->title ?? 'Job';
        $datetime = $interview->interview_datetime->format('d M Y h:i A');
        $this->title = 'Interview Rescheduled';
        $this->message = "Your interview for <strong>{$jobTitle}</strong> has been <strong>rescheduled</strong> to <strong>{$datetime}</strong> via <strong>{$interview->mode}</strong>.";
    }
}
