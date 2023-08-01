<?php

namespace Tests\Console\Commands;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class ScheduleHabitsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function command_successfully_stores_daily_habit_schedules()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

        Habit::factory()->count(1)->create([
            'user_id' => $user->id
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertEquals("2023-07-18", $habitSchedules[0]->scheduled_completion);
        $this->assertEquals("2023-07-19", $habitSchedules[1]->scheduled_completion);
        $this->assertEquals("2023-07-20", $habitSchedules[2]->scheduled_completion);
    }

    /** @test */
    public function command_successfully_stores_weekly_habit_schedule()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

        Habit::factory()->count(1)->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[4]'
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertEquals("2023-07-20", $habitSchedules[0]->scheduled_completion);
    }

    /** @test */
    public function command_successfully_stores_monthly_habit_schedule()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

        Habit::factory()->count(1)->create([
            'user_id' => $user->id,
            'frequency' => Frequency::MONTHLY,
            'occurrence_days' => '["2023-07-17"]'
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertEquals("2023-07-17", $habitSchedules[0]->scheduled_completion);
    }
}
