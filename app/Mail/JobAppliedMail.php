<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobAppliedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $job;
    public $user;
    /**
     * Create a new message instance.
     */
    public function __construct($job, $user)
    {
        $this->job = $job;
        $this->user = $user;

        // Decode JSON if type is string
        if (is_string($this->job->type)) {
            $decoded = json_decode($this->job->type, true);
            $this->job->type = is_array($decoded) ? $decoded : [$this->job->type];
        }
    }
    public function build()
    {
        return $this->from(
            setting('mail_from_address', env('MAIL_FROM_ADDRESS')),
            setting('site_name', env('APP_NAME'))
        )
            ->subject('Your Application for ' . $this->job->title)
            ->view('mail_templates.job_applied')
            ->with([
                'job' => $this->job,
                'user' => $this->user,
            ]);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Application for ' . $this->job->title
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail_templates.job_applied',
            with: [
                'job' => $this->job,
                'user' => $this->user,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
