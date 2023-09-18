<?php

namespace Tests\Http\Traits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Http\CacheKeys;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduledHabitsTraitTest extends TestCase
{
    use RefreshDatabase;
    use ScheduledHabits;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function does_not_retrieve_data_if_a_future_month()
    {
        Carbon::setTestNow("2023-08-01");

        $user = User::factory()->create();
        $habit = Habit::factory()->create();

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-02"
        ]);

        $data = $this->monthlyScheduledHabits($user, "September");

        $this->assertEquals([], $data);

        $this->assertEquals(null, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "September")));
    }

    /** @test */
    public function does_not_update_cache_if_with_caching_is_true()
    {
        Carbon::setTestNow("2023-08-01");

        $user = User::factory()->create();
        $habit = Habit::factory()->create();

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-02"
        ]);

        $this->assertEquals(null, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));

        $this->monthlyScheduledHabits($user, "August");

        $this->assertCount(31, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-04"
        ]);

        $this->monthlyScheduledHabits($user, "August");


        // An interesting way to test this but
        // 1. It tests the structure
        // 2. Tests that it is now in there
        $month = Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August"));

        $this->assertCount(0, $month[3]);
    }

    /** @test */
    public function does_retrieve_data_if_current_month()
    {
        Carbon::setTestNow("2023-08-01");

        $user = User::factory()->create();
        $habit = Habit::factory()->create();

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-02"
        ]);

        $this->assertEquals(null, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));

        $this->monthlyScheduledHabits($user, "August");

        $this->assertCount(31, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));
    }

    /** @test */
    public function updates_cache_if_with_caching_is_true()
    {
        Carbon::setTestNow("2023-08-01");

        $user = User::factory()->create();
        $habit = Habit::factory()->create();

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-02"
        ]);

        $this->assertEquals(null, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));

        $this->monthlyScheduledHabits($user, "August");

        $this->assertCount(31, Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August")));

        HabitSchedule::factory()->create([
            'user_id' => $user->id,
            'habit_id' => $habit->id,
            'scheduled_completion' => "2023-08-04"
        ]);

        $this->monthlyScheduledHabits($user, "August", true);


        // An interesting way to test this but
        // 1. It tests the structure
        // 2. Tests that it is now in there
        $month = Cache::get(CacheKeys::scheduledHabitsForTheMonth($user, "August"));

        $this->assertCount(1, $month[3]);
    }
}
