<?php

namespace Tests\Http\Traits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ScheduledHabitsTest extends TestCase
{
    use DatabaseMigrations;
    use ScheduledHabits;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function gets_users_scheduled_habits()
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
            'scheduled_completion' => '2023-07-19'
        ]);

        $scheduledHabits = $this->getWeeklyScheduledHabits($this->user, '2023-07-23', '2023-07-17');

        $this->assertCount(3, $scheduledHabits);

        $this->assertEquals('2023-07-19', $scheduledHabits[0]['scheduled_completion']);
        $this->assertEquals('2023-07-20', $scheduledHabits[1]['scheduled_completion']);
    }

    /** @test */
    public function gets_users_scheduled_habits_on_monday_and_sunday()
    {
        $this->markTestSkipped();
    }
}
