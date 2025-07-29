<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class InterviewMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $message;

    public function __construct($title, $message)
    {
        $this->title = $title;
        $this->message = $message;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail_templates.interview',
            with: [
                'title' => $this->title,
                'messageBody' => $this->message,
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
