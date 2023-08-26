<?php

namespace App\Console\Commands\Notifications;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\HabitGoalReminderNotification;

class SendHabitGoalReminder extends Command
{
    protected $signature = 'habits:send-habit-goal-reminder';
    protected $description = 'Send notifications to users to complete their habit goals';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $goalPreference = $user->emailPreferences()->get()->first()?->goal_reminder;
            if (! is_null($user->email_verified_at) && isset($goalPreference) && $goalPreference) {
                $user->notify(new HabitGoalReminderNotification());
            }
        });
    }
}
