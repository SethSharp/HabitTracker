<?php

namespace App\Console\Commands\Counters;

use App\Models\User;
use App\Notifications\DailyReminderNotification;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:habit-streak';
    protected $description = 'Counts the habit streak by looking at the scheduled habit (For this habit) and seeing if complete';

    public function handle()
    {
        /**
         * 1. Find / Go through today's scheduled habits
         * 2. Look at the completion status of the habit
         * 3. If completed add to habit streak
         */

        $users = User::all();
        $users->map(function ($user) {
            $scheduledHabits = $user->scheduledHabits()->get();

            $scheduledHabits->map(function ($habit) {
                if ($habit->completed) {
                    $habit->habit()->increment('streak');
                } else {
                    $habit->habit()->streak = 0;
                }
                $habit->save();
            });
        });
    }
}
