<?php

namespace App\Console\Commands\Counters;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class WeeklyStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:weekly-streak';
    protected $description = 'Go through each day of the scheduled habits and add to the current streak, if broken, store current streak as best_streak if higher';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $scheduledHabits = $user->scheduledHabits()->where('scheduled_completion', '<', Carbon::today())->get();
            // TODO: Note somewhere in UI, if no habits for that day, will be a fail... UAT?
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
