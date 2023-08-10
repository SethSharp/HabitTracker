<?php

namespace Tests\Http\Habits;

use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use Tests\Traits\RefreshDatabase;

class RestoreHabitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    /** @test */
    public function cannot_restore_habit_if_not_the_owner()
    {
        $owner = User::factory()->create();
        $user = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $owner->id
        ]);

        $habit->delete();

        $this->actingAs($user)
            ->patch(route('habit.restore', $habit))
            ->assertRedirect();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'deleted_at' => now()
        ]);
    }

    /** @test */
    public function can_restore_habit_if_the_owner()
    {
        $owner = User::factory()->create();

        $habit = Habit::factory()->create([
            'user_id' => $owner->id
        ]);

        $habit->delete();

        $this->actingAs($owner)
            ->patch(route('habit.restore', ['id' => $habit->id]))
            ->assertRedirect();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'deleted_at' => null
        ]);
    }
}
