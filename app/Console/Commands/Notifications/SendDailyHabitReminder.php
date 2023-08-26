<?php

namespace App\Console\Commands\Notifications;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\DailyReminderNotification;

class SendDailyHabitReminder extends Command
{
    protected $signature = 'habits:send-habit-reminder';
    protected $description = 'Emails to users about their habits for the day';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $dailyPreference = $user->emailPreferences()->get()->first()?->daily_reminder;
            if (! is_null($user->email_verified_at) && isset($dailyPreference) && $dailyPreference) {
                $user->notify(new DailyReminderNotification());
            }
        });
    }
}
