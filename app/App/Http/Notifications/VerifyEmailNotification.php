<?php

namespace App\App\Http\Notifications;

use App\App\Http\Events\RegisteredEvent;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends Notification
{
    public function handle(RegisteredEvent $event)
    {
        //        $user = $event->user;
        //        $user->sendEmailVerificationNotification();
    }
}
