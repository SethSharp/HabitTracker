<?php

namespace App\Console\Commands\Counters;

use App\Models\User;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class WeeklyStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:weekly-streak';
    protected $description = 'Go through each day of the scheduled habits and add to the current streak, if broken, store current streak as best_streak if higher';

    public function handle()
    {
        /**
         * 1. Find / Go through today's scheduled habits
         * 2. If all completed, increase streak counter
         * 3. If not all completed, update best_streak if streak is greater
         */
        $users = User::all();
        $users->map(function ($user) {
            $scheduledHabits = $user->scheduledHabits()->get();
            $streak = true;
            $scheduledHabits->map(function ($habit) use (&$streak) {
                if ($habit->completed == 0) {
                    $streak = false;
                }
            });

            if ($streak) {
                $user->increment('streak');

                if ($user->streak > $user->best_streak) {
                    $user->best_streak = $user->streak;
                }
            } else {
                $user->streak = 0;
            }
            $user->save();
        });
    }
}
