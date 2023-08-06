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

class HabitStreakTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function streak_is_reset_to_0_if_not_completed()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

        Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'user_id' => $user->id,
            'streak' => 0
        ]);
    }

    /** @test */
    public function streak_is_stored_if_completed()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();

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

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'user_id' => $user->id,
            'streak' => 1
        ]);
    }

    /** @test */
    public function multiple_users_habit_streaks_are_stored_if_completed()
    {
        Carbon::setTestNow(Carbon::parse("2023-07-17"));

        $user = User::factory()->create();
        $user2 = User::factory()->create();

        Habit::factory()->create([
            'user_id' => $user->id,
            'occurrence_days' => '[1]'
        ]);

        Habit::factory()->create([
            'user_id' => $user2->id,
            'occurrence_days' => '[1]'
        ]);

        // Setup scheduled habits
        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();

        $scheduledHabits = HabitSchedule::all();

        $scheduledHabits[0]->update(['completed' => 1]);

        Carbon::setTestNow(Carbon::parse("2023-07-18"));

        $this->artisan('counters:habit-streak')
            ->assertSuccessful();

        $this->assertDatabaseHas('habits', [
            'id' => $scheduledHabits[0]->id,
            'user_id' => $user->id,
            'streak' => 1
        ]);

        $this->assertDatabaseHas('habits', [
            'id' => $scheduledHabits[1]->id,
            'user_id' => $user2->id,
            'streak' => 0
        ]);
    }
}
