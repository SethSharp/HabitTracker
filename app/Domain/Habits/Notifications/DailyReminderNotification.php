<?php

namespace App\Domain\Habits\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DailyReminderNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->line('Daily reminder to complete habits')
            ->action('Do that here!', route('dashboard'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
