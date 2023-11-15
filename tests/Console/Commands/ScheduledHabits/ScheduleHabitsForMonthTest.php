<?php

namespace Tests\Console\Commands\ScheduledHabits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;
use App\Domain\Habits\Models\Habit;

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

        $this->artisan('habits:schedule')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        // 5 weeks, where there is a tue/wed/thur
        $this->assertCount(15, $habitSchedules);

        // Week 1
        $this->assertEquals("2023-08-01", $habitSchedules[0]->scheduled_completion);
        $this->assertEquals("2023-08-02", $habitSchedules[1]->scheduled_completion);
        $this->assertEquals("2023-08-03", $habitSchedules[2]->scheduled_completion);

        // Week 2
        $this->assertEquals("2023-08-08", $habitSchedules[3]->scheduled_completion);
        $this->assertEquals("2023-08-09", $habitSchedules[4]->scheduled_completion);
        $this->assertEquals("2023-08-10", $habitSchedules[5]->scheduled_completion);

        // Week 3
        $this->assertEquals("2023-08-15", $habitSchedules[6]->scheduled_completion);
        $this->assertEquals("2023-08-16", $habitSchedules[7]->scheduled_completion);
        $this->assertEquals("2023-08-17", $habitSchedules[8]->scheduled_completion);

        // Week 4
        $this->assertEquals("2023-08-22", $habitSchedules[9]->scheduled_completion);
        $this->assertEquals("2023-08-23", $habitSchedules[10]->scheduled_completion);
        $this->assertEquals("2023-08-24", $habitSchedules[11]->scheduled_completion);

        // Week 5
        $this->assertEquals("2023-08-29", $habitSchedules[12]->scheduled_completion);
        $this->assertEquals("2023-08-30", $habitSchedules[13]->scheduled_completion);
        $this->assertEquals("2023-08-31", $habitSchedules[14]->scheduled_completion);
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

        $this->artisan('habits:schedule')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertCount(5, $habitSchedules);

        $this->assertEquals("2023-08-02", $habitSchedules[0]->scheduled_completion);
        $this->assertEquals("2023-08-09", $habitSchedules[1]->scheduled_completion);
        $this->assertEquals("2023-08-16", $habitSchedules[2]->scheduled_completion);
        $this->assertEquals("2023-08-23", $habitSchedules[3]->scheduled_completion);
        $this->assertEquals("2023-08-30", $habitSchedules[4]->scheduled_completion);
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

        $this->artisan('habits:schedule')
            ->assertSuccessful();

        $habitSchedules = HabitSchedule::all();

        $this->assertEquals("2023-08-17", $habitSchedules[0]->scheduled_completion);
    }
}
