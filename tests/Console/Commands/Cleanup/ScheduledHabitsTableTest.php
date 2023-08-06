<?php

namespace Tests\Console\Commands\Cleanup;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class ScheduledHabitsTableTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function all_schedules_are_removed_from_last_week_only()
    {
    }
}
