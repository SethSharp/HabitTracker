<?php

namespace Tests\Http\Traits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduledHabitsTest extends TestCase
{
    use DatabaseMigrations;
    use ScheduledHabits;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function gets_users_scheduled_habits()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function gets_users_scheduled_habits_on_monday_and_sunday()
    {
        $this->markTestSkipped();
    }
}
