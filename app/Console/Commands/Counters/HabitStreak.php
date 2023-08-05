<?php

namespace App\Console\Commands\Counters;

use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitStreak extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:habit-streak';
    protected $description = 'Counts the habit streak by looking at the scheduled habit (For this habit) and seeing if complete';

    public function handle()
    {
    }
}
