<?php

namespace Tests\Console\Commands\Counters;

use App\Console\Commands\Counters\HabitStreak;
use App\Models\EmailPreferences;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Models\User;
use App\Notifications\DailyReminderNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class WeeklyStreakTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_streak_is_reset_to_0_if_not_completed()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create([
            'streak' => 2,
            'best_streak' => 4
        ]);

        Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $this->artisan('counters:weekly-streak')
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
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create([
            'streak' => 4,
            'best_streak' => 4
        ]);

        Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $scheduledHabit = HabitSchedule::all()->first();

        $scheduledHabit->update(['completed' => 1]);

        $this->artisan('counters:weekly-streak')
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
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create([
            'streak' => 3,
            'best_streak' => 5,
        ]);

        Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $scheduledHabit = HabitSchedule::all()->first();

        $scheduledHabit->update(['completed' => 1]);

        $this->artisan('counters:weekly-streak')
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
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user1 = User::factory()->create([
            'streak' => 3,
            'best_streak' => 5,
        ]);

        $user2 = User::factory()->create([
            'streak' => 1,
            'best_streak' => 4,
        ]);

        Habit::factory()->create([
            'user_id' => $user1->id,
            'occurrence_days' => '[1]'
        ]);

        Habit::factory()->create([
            'user_id' => $user2->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $scheduledHabits = HabitSchedule::all();

        $scheduledHabits[0]->update(['completed' => 1]);

        $this->artisan('counters:weekly-streak')
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
