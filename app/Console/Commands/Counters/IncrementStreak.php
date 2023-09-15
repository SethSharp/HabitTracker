<?php

namespace App\Console\Commands\Counters;

use App\Models\User;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class IncrementStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:inc-habit-streak';
    protected $description = 'Testing command';

    public function handle()
    {
        $users = User::all();
        $users->map(function ($user) {
            $user->update([
                'streak' => $user->streak + 1
            ]);

            $user->save();
        });
    }
}
