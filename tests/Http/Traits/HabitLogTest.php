<?php

namespace Tests\Http\Traits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class HabitLogTest extends TestCase
{
    use DatabaseMigrations;
    use HabitLog;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function weekly_log_is_cached()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'frequency' => Frequency::DAILY->value,
            'occurrence_days' => '[1,2,3]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-16'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-17'
        ]);

        $scheduledHabits = $this->getWeeklyLog($this->user, '2023-07-15', '2023-07-20');
        $this->assertCount(2, $scheduledHabits);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-18'
        ]);

        $scheduledHabits = $this->getWeeklyLog($this->user, '2023-07-15', '2023-07-20');
        $this->assertCount(2, $scheduledHabits);
    }

    /** @test */
    public function weekly_log_cache_is_updated_after_one_day()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'frequency' => Frequency::DAILY->value,
            'occurrence_days' => '[1,2,3]'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-16'
        ]);

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-17'
        ]);

        $scheduledHabits = $this->getWeeklyLog($this->user, '2023-07-15', '2023-07-20');
        $this->assertCount(2, $scheduledHabits);

        Carbon::setTestNow(now()->addDay()->addSecond());

        HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => '2023-07-18'
        ]);

        $scheduledHabits = $this->getWeeklyLog($this->user, '2023-07-15', '2023-07-20');
        $this->assertCount(3, $scheduledHabits);
    }
}
