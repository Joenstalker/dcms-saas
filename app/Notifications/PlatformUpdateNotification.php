<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PlatformUpdateNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public string $updateType;
    public string $version;
    public ?string $releaseNotes;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $updateType, string $version, ?string $releaseNotes = null)
    {
        $this->updateType = $updateType;
        $this->version = $version;
        $this->releaseNotes = $releaseNotes;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = match($this->updateType) {
            'available' => $this->updateAvailableMail(),
            'in_progress' => $this->updateInProgressMail(),
            'completed' => $this->updateCompletedMail(),
            'failed' => $this->updateFailedMail(),
            default => $this->updateAvailableMail(),
        };

        return $mail;
    }

    /**
     * Update available email
     */
    private function updateAvailableMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('New Platform Update Available - DCMS')
            ->greeting('Hello!')
            ->line('A new platform update is available for the DCMS system.')
            ->line("Version: {$this->version}")
            ->when($this->releaseNotes, function ($mail) {
                return $mail->line('Release Notes:')
                    ->line($this->releaseNotes);
            })
            ->action('View Details', route('admin.platform-updates.index'))
            ->line('You can deploy this update from the admin dashboard.')
            ->line('Thank you for using our platform!');
    }

    /**
     * Update in progress email
     */
    private function updateInProgressMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Platform Update In Progress - DCMS')
            ->greeting('Attention!')
            ->line('A platform update is currently being deployed.')
            ->line("Version: {$this->version}")
            ->line('You may experience brief downtime during this process.')
            ->line('We appreciate your patience!');
    }

    /**
     * Update completed email
     */
    private function updateCompletedMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Platform Update Completed - DCMS')
            ->greeting('Great news!')
            ->line('The platform has been successfully updated.')
            ->line("New Version: {$this->version}")
            ->when($this->releaseNotes, function ($mail) {
                return $mail->line('What\'s New:')
                    ->line($this->releaseNotes);
            })
            ->line('Thank you for using our platform!');
    }

    /**
     * Update failed email
     */
    private function updateFailedMail(): MailMessage
    {
        return (new MailMessage)
            ->subject('Platform Update Failed - DCMS')
            ->greeting('Attention Required!')
            ->line('A platform update attempt has failed.')
            ->line("Version: {$this->version}")
            ->line('The system has been rolled back to the previous version.')
            ->line('Please check the admin dashboard for details.')
            ->action('View Details', route('admin.platform-updates.index'))
            ->line('If the issue persists, please contact support.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'platform_update',
            'update_type' => $this->updateType,
            'version' => $this->version,
            'message' => $this->getMessage(),
            'release_notes' => $this->releaseNotes,
        ];
    }

    /**
     * Get the notification message based on type
     */
    private function getMessage(): string
    {
        return match($this->updateType) {
            'available' => "New platform update v{$this->version} is available",
            'in_progress' => "Platform update to v{$this->version} is in progress",
            'completed' => "Platform successfully updated to v{$this->version}",
            'failed' => "Platform update to v{$this->version} failed",
            default => "Platform update notification",
        };
    }
}
