<?php

namespace Tests\Http\ScheduledHabits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class UpdateScheduledHabitControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function updates_the_correct_scheduled_habit()
    {
        Carbon::setTestNow("2023-08-27");

        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[1]',
            'scheduled_to' => "2023-08-27",
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $scheduledHabit2 = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $this->actingAs($user)
            ->post(route('schedule.update'), [
                'habits' => [$scheduledHabit->id]
            ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit->id,
            'completed' => 1,
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit2->id,
            'completed' => 0,
        ]);
    }

    /** @test */
    public function updates_all_the_habits()
    {
        Carbon::setTestNow("2023-08-27");

        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[1]',
            'scheduled_to' => "2023-08-27",
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $scheduledHabit2 = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $this->actingAs($user)
            ->post(route('schedule.update'), [
                'habits' => [$scheduledHabit2->id, $scheduledHabit->id]
            ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit->id,
            'completed' => 1,
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit2->id,
            'completed' => 1,
        ]);
    }

    /** @test */
    public function updates_none_of_the_habits()
    {
        Carbon::setTestNow("2023-08-27");

        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[1]',
            'scheduled_to' => "2023-08-27",
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $scheduledHabit2 = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        $this->actingAs($user)
            ->post(route('schedule.update'), [
                'habits' => null
            ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit->id,
            'completed' => 0,
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit2->id,
            'completed' => 0,
        ]);
    }

    /** @test */
    public function habit_goal_scheduled_to_is_set_to_null_if_last_schedule()
    {
        $this->markTestSkipped();
        //        Carbon::setTestNow("2023-08-27");
        //
        //        $user = User::factory()->create();
        //
        //        $habit = Habit::factory()->create([
        //            'user_id' => $user->id,
        //            'frequency' => Frequency::WEEKLY,
        //            'occurrence_days' => '[1]',
        //            'scheduled_to' => "2023-08-27",
        //        ]);
        //
        //        $scheduledHabit = HabitSchedule::factory()->create([
        //            'user_id' => $user->id,
        //            'habit_id' => $habit->id,
        //            'scheduled_completion' => "2023-08-27"
        //        ]);
        //
        //        $this->actingAs($user)
        //            ->post(route('schedule.update'), [
        //                'habits' => [$scheduledHabit->id]
        //            ]);
        //
        //        $this->assertDatabaseHas('habit_schedules', [
        //            'id' => $scheduledHabit->id,
        //            'completed' => 1,
        //        ]);
        //
        //        $this->assertDatabaseHas('habits', [
        //            'id' => $habit->id,
        //            'scheduled_to' => null,
        //        ]);
    }

    /** @test */
    public function habit_goal_scheduled_to_is_not_set_to_null_if_last_schedule()
    {
        Carbon::setTestNow("2023-08-27");

        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'frequency' => Frequency::WEEKLY,
            'occurrence_days' => '[1]',
            'scheduled_to' => "2023-08-28",
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-27"
        ]);

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-28"
        ]);

        $this->actingAs($user)
            ->post(route('schedule.update'), [
                'habits' => [$scheduledHabit->id]
            ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit->id,
            'completed' => 1,
        ]);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'scheduled_to' => "2023-08-28",
        ]);
    }
}
