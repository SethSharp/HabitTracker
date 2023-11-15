<?php

namespace Tests\Http\ScheduledHabits;

use Tests\TestCase;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;
use App\Domain\Habits\Models\Habit;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class UnCompletedHabitsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function must_be_owner_to_uncomplete()
    {
        $anotherUser = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'completed' => true
        ]);

        $this->actingAs($anotherUser)
            ->post(route('schedule.uncomplete', [ 'habitSchedule' => $scheduledHabit->id ]))
            ->assertForbidden();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'completed' => true
        ]);
    }

    /** @test */
    public function habit_must_be_in_the_past()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $habitSchedule = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'completed' => true
        ]);

        $this->actingAs($this->user)
            ->postJson(route('schedule.uncomplete', [ 'habitSchedule' => $habitSchedule->id ]))
            ->assertForbidden();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'completed' => true
        ]);
    }

    /** @test */
    public function can_uncomplete_habit()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $habitSchedule = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => now(),
            'completed' => true
        ]);

        $this->actingAs($this->user)
            ->postJson(route('schedule.uncomplete', [ 'habitSchedule' => $habitSchedule->id ]))
            ->assertOk();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'completed' => false
        ]);
    }
}
