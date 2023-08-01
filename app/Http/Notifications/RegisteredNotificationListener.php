<?php

namespace App\Http\Notifications;

use App\Http\Events\RegisteredEvent;
use App\Mail\Registration;
use App\Models\User;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class RegisteredNotificationListener extends Notification
{
    public function handle(RegisteredEvent $event)
    {
        $user = $event->user;

        Mail::to($user->email)->send(new Registration($user->name));
    }
}
