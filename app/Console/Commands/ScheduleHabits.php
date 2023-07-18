<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ScheduleHabits extends Command
{
    protected $signature = 'app:schedule-habits';
    protected $description = 'Set habits up for the week, which includes looking for habits occurring in the current week (Daily, weekly or monthly habits';

    public function handle()
    {
        $users = User::all()->first();
        ray($users->habits()->get()->toArray());
    }
}
