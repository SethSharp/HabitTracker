<?php

namespace App\Console\Commands\Counters;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:habit-streak';
    protected $description = 'Counts the habit streak by looking at the scheduled habit (For this habit) and seeing if complete';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $scheduledHabits = $user->scheduledHabits()->where('scheduled_completion', Carbon::today())->get();

            $scheduledHabits->map(function ($habit) {
                if ($habit->completed == 1) {
                    $habit->habit->increment('streak');
                } else {
                    $habit->habit->streak = 0;
                }
                $habit->habit->save();
            });
        });
    }
}
