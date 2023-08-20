<?php

namespace Tests\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class ScheduleHabitsForMonthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function command_successfully_stores_daily_habit_schedules()
    {
        Carbon::setTestNow(Carbon::parse("2023-08-01"));

        $user = User::factory()->create();

        Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::DAILY,
            'occurrence_days' => '[2,3,4]',
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        // 5 weeks, where there is a tue/wed/thur
        $this->assertCount(15, $habitSchedules);
    }

    /** @test */
    public function command_successfully_stores_weekly_habit_schedule()
    {
        Carbon::setTestNow(Carbon::parse("2023-08-01"));

        $user = User::factory()->create();

        Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[3]'
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertCount(5, $habitSchedules);
    }

    /** @test */
    public function command_successfully_stores_monthly_habit_schedule()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

        Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::MONTHLY,
            'occurrence_days' => '["2023-08-17"]'
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertEquals("2023-08-17", $habitSchedules[0]->scheduled_completion);
    }
}
