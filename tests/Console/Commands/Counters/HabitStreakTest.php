<?php

namespace Tests\Console\Commands\Counters;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class HabitStreakTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function streak_is_reset_to_0_if_not_completed()
    {
        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]',
            'streak' => 1
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-02"));

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'user_id' => $user->id,
            'streak' => 0,
        ]);
    }

    /** @test */
    public function streak_is_stored_if_completed()
    {
        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[3]',
            'streak' => 1
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-02"
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-01"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-02"));

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'user_id' => $user->id,
            'streak' => 2
        ]);
    }

    /** @test */
    public function handle_users_with_different_completion_statuses()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $habit1 = Habit::factory()->create([
            'user_id' => $user1->id,
            'occurrence_days' => '[3]',
            'streak' => 1
        ]);

        $habit2 = Habit::factory()->create([
            'user_id' => $user2->id,
            'occurrence_days' => '[3]',
            'streak' => 1,
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit1->id,
            'user_id' => $user1->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-02"
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit2->id,
            'user_id' => $user2->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-02"));

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'user_id' => $user1->id,
            'streak' => 2
        ]);

        $this->assertDatabaseHas('habits', [
            'user_id' => $user2->id,
            'streak' => 0
        ]);
    }
}
