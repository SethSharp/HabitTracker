<?php

namespace App\Console\Commands\Cleanup;

use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduledHabitsTable extends Command
{
    use ScheduledHabits;
    protected $signature = 'cleanup:scheduled-habits-table';
    protected $description = '';

    public function handle()
    {

    }
}
