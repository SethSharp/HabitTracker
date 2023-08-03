<?php

namespace App\Console\Commands\Testing;

use App\Models\User;
use App\Notifications\DailyReminderNotification;
use Illuminate\Console\Command;
use App\Notifications\RegistrationNotification;
use App\Http\Controllers\Traits\ScheduledHabits;

class SendDailyReminder extends Command
{
    use ScheduledHabits;

    protected $signature = 'testing:daily-reminder';
    protected $description = 'Send daily reminder email';

    public function handle()
    {
        $user = User::all()->first();
        if (! is_null($user->email_verified_at)) {
            $user->notify(new DailyReminderNotification());
        }
    }
}
