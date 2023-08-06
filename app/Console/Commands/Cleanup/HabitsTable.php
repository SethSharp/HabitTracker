<?php

namespace App\Console\Commands\Cleanup;

use App\Models\Habit;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class HabitsTable extends Command
{
    use ScheduledHabits;
    protected $signature = 'cleanup:deleted-habits-table';
    protected $description = 'Once the week is over and counters have been updated, remove the habits that have been soft deleted';

    public function handle()
    {
        // Get soft deleted habits
        $habits = Habit::withTrashed()
            ->withTrashed()
            ->where('deleted_at', '!=', null)
            ->get();

        ray($habits);

        $habits->map(fn ($habit) => $habit->forceDelete());
    }
}
