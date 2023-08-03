<?php

namespace App\Http\Notifications;

use Illuminate\Auth\Events\Verified;
use Illuminate\Notifications\Notification;
use App\Notifications\RegistrationNotification;

class RegisteredNotificationListener extends Notification
{
    public function handle(Verified $event)
    {
        $user = $event->user;
        $user->notify(new RegistrationNotification());
    }
}
