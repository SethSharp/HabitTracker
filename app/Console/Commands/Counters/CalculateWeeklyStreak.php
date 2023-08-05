<?php

namespace App\Console\Commands\Counters;

use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class CalculateWeeklyStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:weekly-streak';
    protected $description = 'Go through each day of the scheduled habits and add to the current streak, if broken, store current streak as best_streak if higher';

    public function handle()
    {
    }
}
