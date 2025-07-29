<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class InterviewRevokedNotification extends Notification
{
    public $title;
    public $message;

    public function __construct($interview)
    {
        $jobTitle = $interview->job->title ?? 'Job';
        $this->title = 'Interview Revoked';
        $this->message = "The interview scheduled for <strong>{$jobTitle}</strong> has been <strong>cancelled</strong> by the recruiter.";
    }
}
