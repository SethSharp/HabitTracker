<?php

namespace App\App\Console\Commands\Counters;

use Carbon\Carbon;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class HabitStreak extends Command
{
    use ScheduledHabits;

    protected $signature = 'counters:habit-streak';
    protected $description = 'Counts the habit streak by looking at the habits from yesterday and record if complete';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $scheduledHabits = $user->scheduledHabits()->where('scheduled_completion', Carbon::now()->subDay())->get();

            foreach ($scheduledHabits as $scheduledHabit) {
                if ($scheduledHabit->completed == 1) {
                    $scheduledHabit->habit->increment('streak');
                    $scheduledHabit->habit->increment('completed');
                } else {
                    $scheduledHabit->habit->streak = 0;
                    $scheduledHabit->habit->increment('missed');
                }
                $scheduledHabit->habit->save();
            }
        });
    }
}
