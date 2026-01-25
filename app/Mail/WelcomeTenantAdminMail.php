<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class WelcomeTenantAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $tenantName;
    public $loginUrl;
    public $email;
    public $password;

    /**
     * Create a new message instance.
     */
    public function __construct(string $tenantName, string $loginUrl, string $email, string $password)
    {
        $this->tenantName = $tenantName;
        $this->loginUrl = $loginUrl;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Welcome to DCMS – Your Clinic Portal',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome-tenant-admin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath(public_path('images/dcms-logo.png'))
                ->as('dcms-logo.png')
                ->withMime('image/png'),
        ];
    }
}
