<?php

namespace App\Console\Commands\Cleanup;

use App\Models\HabitSchedule;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduledHabitsTable extends Command
{
    use ScheduledHabits;
    protected $signature = 'cleanup:scheduled-habits-table';
    protected $description = 'Once the week is over and counters have been updated, remove the previous week of scheduled habits';

    public function handle()
    {
        $scheduledHabits = HabitSchedule::where('scheduled_completion', '<', date('Y-m-d'))
            ->get();

        $scheduledHabits->each->delete();
    }
}
