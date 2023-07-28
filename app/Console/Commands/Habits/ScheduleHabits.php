<?php

namespace App\Console\Commands\Habits;

use App\Models\User;
use Illuminate\Console\Command;

class ScheduleHabits extends Command
{
    protected $signature = 'habits:schedule-habits';
    protected $description = 'Set habits up for the week, which includes looking for habits occurring in the current week (Daily, weekly or monthly habits';

    public function handle()
    {
        User::factory()->create();
    }
}
