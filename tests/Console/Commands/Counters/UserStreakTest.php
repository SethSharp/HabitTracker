<?php

namespace Tests\Console\Commands\Counters;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;

class UserStreakTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_streak_is_reset_to_0_if_not_completed()
    {
        $user = User::factory()->create([
            'streak' => 2,
            'best_streak' => 4
        ]);

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'scheduled_completion' => "2023-08-06"
        ]);

        // Simulates the monday that it is run on
        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('counters:user-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'streak' => 0,
            'best_streak' => 4,
        ]);
    }

    /** @test */
    public function user_best_streak_is_updated_to_current_streak_if_higher()
    {
        $user = User::factory()->create([
            'streak' => 4,
            'best_streak' => 4
        ]);

        $habit1 = Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit1->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-06"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('counters:user-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'streak' => 5,
            'best_streak' => 5,
        ]);
    }

    /** @test */
    public function user_streak_is_stored_if_completed()
    {
        $user = User::factory()->create([
            'streak' => 3,
            'best_streak' => 5,
        ]);

        $habit = Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $user->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-06"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-7"));

        $this->artisan('counters:user-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'streak' => 4,
            'best_streak' => 5,
        ]);
    }

    /** @test */
    public function multiple_users_streak_are_stored_if_completed()
    {
        $user1 = User::factory()->create([
            'streak' => 3,
            'best_streak' => 5,
        ]);

        $user2 = User::factory()->create([
            'streak' => 1,
            'best_streak' => 4,
        ]);

        $habit1 = Habit::factory()->create([
            'user_id' => $user1->id,
            'occurrence_days' => '[3]'
        ]);

        $habit2 = Habit::factory()->create([
            'user_id' => $user2->id,
            'occurrence_days' => '[3]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit1->id,
            'user_id' => $user1->id,
            'completed' => 1,
            'scheduled_completion' => "2023-08-06"
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit2->id,
            'user_id' => $user2->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-06"
        ]);

        Carbon::setTestNow(Carbon::parse("2023-08-07"));

        $this->artisan('counters:user-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('users', [
            'id' => $user1->id,
            'streak' => 4,
            'best_streak' => 5,
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $user2->id,
            'streak' => 0,
            'best_streak' => 4,
        ]);
    }
}
