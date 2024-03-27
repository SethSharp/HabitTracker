<?php

namespace App\App\Console\Commands\Counters;

use Carbon\Carbon;
use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class UserStreak extends Command
{
    use ScheduledHabits;

    protected $signature = 'counters:user-streak';
    protected $description = 'Look at all scheduled habits for yesterday, record whether all streaks were completed or not';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            // Go through scheduled habits
            // if a user doesn't have a habit scheduled for a certain day, it should not reset the streak

            // get scheduled habits for yesterday
            $scheduledHabits = $user->scheduledHabits()->where('scheduled_completion', '=', Carbon::now()->subDay())->get();

            $streak = true;
            foreach ($scheduledHabits as $habit) {
                if ($habit->completed == 0) {
                    $streak = false;
                    break;
                }
            }

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
