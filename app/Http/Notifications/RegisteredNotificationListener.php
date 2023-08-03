<?php

namespace App\Http\Notifications;

use App\Notifications\RegistrationNotification;
use Illuminate\Auth\Events\Verified;
use Illuminate\Notifications\Notification;

class RegisteredNotificationListener extends Notification
{
    public function handle(Verified $event)
    {
        $event->user->notify(RegistrationNotification::class);
    }
}
