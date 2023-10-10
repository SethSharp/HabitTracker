<?php

namespace App\Console\Commands;

use App\Http\Controllers\Traits\HabitStorage;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\HabitSchedule;
use Illuminate\Console\Command;

class Fix extends Command
{
    use ScheduledHabits;
    use HabitStorage;

    protected $signature = 'fix:things';
    protected $description = 'Remove all scheduled habits for the month of October';

    public function handle()
    {
        $habitSchedules = HabitSchedule::where('scheduled_completion', '>=', '2023-10-01');

        $habitSchedules->delete();
    }
}
