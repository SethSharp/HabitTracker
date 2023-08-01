<?php

namespace App\Http\Notifications;

use App\Mail\Registration;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notification;

class RegisteredNotificationListener extends Notification
{
    public function handle(Verified $event)
    {
        $user = $event->user;

        Mail::to($user->email)->send(new Registration($user->name));
    }
}
