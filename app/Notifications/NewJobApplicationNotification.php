<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Job;
use App\Models\JobApplication;

class NewJobApplicationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $job;
    public $application;

    public function __construct(Job $job, JobApplication $application)
    {
        $this->job = $job;
        $this->application = $application;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Application for Your Job Posting')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new candidate has applied for your job: ' . $this->job->title)
            ->line('Candidate: ' . $this->application->first_name . ' ' . $this->application->last_name)
            ->line('Email: ' . $this->application->email)
            ->action('View Applicants', url('/recruiter/jobs/' . $this->job->id . '/applicants'))
            ->line('Thank you for using HireMe!');
    }

    public function toArray($notifiable)
    {
        return [
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'applicant_name' => $this->application->first_name . ' ' . $this->application->last_name,
            'applicant_email' => $this->application->email,
        ];
    }
}
