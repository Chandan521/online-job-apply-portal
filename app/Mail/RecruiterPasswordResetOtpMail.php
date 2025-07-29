<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;

class RecruiterPasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;

    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Define the mail envelope (sender and subject).
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(setting('site_email', config('mail.from.address')), setting('site_name', config('app.name'))),
            subject: 'Recruiter Password Reset OTP'
        );
    }

    /**
     * Define the email content view.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mail_templates.recruiter_otp',
            with: [
                'otp' => $this->otp,
            ],
        );
    }

    /**
     * Attachments (if any).
     */
    public function attachments(): array
    {
        return [];
    }
}
