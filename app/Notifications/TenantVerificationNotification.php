<?php

declare(strict_types=1);

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TenantVerificationNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Tenant $tenant,
        public string $verificationToken
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $verificationUrl = route('tenant.verification.verify', [
            'token' => $this->verificationToken,
            'email' => $this->tenant->email,
        ]);

        return (new MailMessage)
            ->subject('Verify Your Clinic Registration - DCMS')
            ->greeting('Hello ' . $this->tenant->name . '!')
            ->line('Thank you for registering your clinic with DCMS.')
            ->line('Please click the button below to verify your email address and activate your tenant account.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('If you did not create an account, no further action is required.')
            ->line('This verification link will expire in 24 hours.');
    }
}
