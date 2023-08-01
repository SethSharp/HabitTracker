<?php

namespace App\Console\Commands\Habits;

use App\Models\User;
use App\Mail\HabitReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendHabitScheduleReminder extends Command
{
    protected $signature = 'habits:send-habit-reminder';
    protected $description = 'Emails to users about their habits for the day';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $dailyPreference = $user->emailPreferences()->get()->first()?->daily_reminder;
            if (! is_null($user->email_verified_at) && isset($dailyPreference) && $dailyPreference) {
                Mail::to($user->email)->send(new HabitReminder($user->name));
            }
        });
    }
}
