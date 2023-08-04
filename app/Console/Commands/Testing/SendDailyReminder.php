<?php

namespace App\Console\Commands\Testing;

use App\Models\User;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Notifications\DailyReminderNotification;

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
