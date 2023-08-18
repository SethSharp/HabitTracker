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
        $this->markTestSkipped();
        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id
        ]);

        $schedule1 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02"
        ]);

        $schedule2 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-03"
        ]);

        $schedule3 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-04",
        ]);

        // Schedule in next week
        $schedule4 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-08"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('cleanup:scheduled-habits-table')
            ->assertSuccessful();

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule1->id,
        ]);

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule2->id,
        ]);

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule3->id,
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $schedule4->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function already_deleted_schedules_are_removed_from_last_week_only()
    {
        $this->markTestSkipped();
        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id
        ]);

        $schedule1 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02",
            'deleted_at' => "2023-08-02",
        ]);

        $schedule2 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-03",
            'deleted_at' => "2023-08-02",
        ]);

        $schedule3 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-04",
            'deleted_at' => "2023-08-02",
        ]);

        // Schedule in next week
        $schedule4 = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-08",
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('cleanup:scheduled-habits-table')
            ->assertSuccessful();

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule1->id,
        ]);

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule2->id,
        ]);

        $this->assertDatabaseMissing('habit_schedules', [
            'id' => $schedule3->id,
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $schedule4->id,
            'deleted_at' => null
        ]);
    }
}
