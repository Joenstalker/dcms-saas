<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StaffInvitationNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public string $tempPassword,
        public string $clinicName,
        public string $loginUrl,
        public string $role
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $roleLabel = ucfirst($this->role);

        return (new MailMessage)
            ->subject("You've been invited to {$this->clinicName} - {$roleLabel} Account")
            ->view('emails.staff-invitation', [
                'name' => $notifiable->name,
                'email' => $notifiable->email,
                'tempPassword' => $this->tempPassword,
                'clinicName' => $this->clinicName,
                'loginUrl' => $this->loginUrl,
                'role' => $this->role,
                'roleLabel' => $roleLabel,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tempPassword' => $this->tempPassword,
            'clinicName' => $this->clinicName,
            'loginUrl' => $this->loginUrl,
            'role' => $this->role,
        ];
    }
}
