<?php

namespace Tests\Http\ScheduledHabits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Tests\Traits\RefreshDatabase;

class CancelHabitsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function must_be_owner_to_cancel()
    {
        $anotherUser = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $scheduledHabit = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id
        ]);

        $this->actingAs($anotherUser)
            ->post(route('schedule.cancel', [ 'habitSchedule' => $scheduledHabit->id ]))
            ->assertForbidden();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'completed' => false
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
        ]);

        $this->actingAs($this->user)
            ->postJson(route('schedule.complete', [ 'habitSchedule' => $habitSchedule->id ]))
            ->assertForbidden();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'cancelled' => false,
            'completed' => false
        ]);
    }

    /** @test */
    public function can_cancel_habit()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $habitSchedule = HabitSchedule::factory()->create([
            'habit_id' => $habit->id,
            'user_id' => $this->user->id,
            'scheduled_completion' => now(),
        ]);

        $this->actingAs($this->user)
            ->postJson(route('schedule.cancel', [ 'habitSchedule' => $habitSchedule->id ]))
            ->assertOk();

        $this->assertDatabaseHas('habit_schedules', [
            'user_id' => $this->user->id,
            'cancelled' => true,
            'completed' => false
        ]);
    }
}
