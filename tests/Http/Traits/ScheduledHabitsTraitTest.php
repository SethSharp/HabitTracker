<?php

namespace Tests\Http\Traits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Support\CacheKeys;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;
use App\Domain\Habits\Models\Habit;
use Illuminate\Support\Facades\Cache;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\App\Http\Controllers\Traits\ScheduledHabits;

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

        $this->assertCount(31, $this->monthlyScheduledHabits($user, "August"));
    }
}
