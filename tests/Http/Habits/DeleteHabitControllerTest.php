<?php

namespace Tests\Http\Habits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\HabitSchedule;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;
use App\Domain\Habits\Models\Habit;

class DeleteHabitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Habit $habit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->habit = Habit::factory()->create(['user_id' => $this->user->id]);
    }

    /** @test */
    public function must_be_authenticated()
    {
        $this->delete(route('habit.delete', ['habit' => $this->habit]))
            ->assertRedirect('/login');
    }

    /** @test */
    public function habit_is_not_delete_if_not_authenticated()
    {
        $this->delete(route('habit.delete', ['habit' => $this->habit]))
            ->assertRedirect('/login');

        $this->assertDatabaseHas('habits', [
            'id' => $this->habit->id
        ]);
    }

    /** @test */
    public function deleting_habit_takes_user_to_habits_view()
    {
        $this->actingAs($this->user)
            ->delete(route('habit.delete', ['habit' => $this->habit]))
            ->assertRedirect(route('habit'));
    }

    /** @test */
    public function correct_habit_is_soft_deleted()
    {
        $habit = Habit::factory()->create(['user_id' => $this->user->id]);

        $this->actingAs($this->user)
            ->delete(route('habit.delete', ['habit' => $this->habit]))
            ->assertRedirect(route('habit'));

        $this->assertDatabaseHas('habits', [
            'id' => $this->habit->id,
            'deleted_at' => now()
        ]);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function correct_scheduled_habits_are_removed()
    {
        Carbon::setTestNow(Carbon::parse("2023-08-03"));

        $scheduledHabit1 = HabitSchedule::factory()->create([
            'habit_id' => $this->habit->id,
            'user_id' => $this->user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02"
        ]);

        $habit2 = Habit::factory()->create(['user_id' => $this->user->id]);

        $scheduledHabit2 = HabitSchedule::factory()->create([
            'habit_id' => $habit2->id,
            'user_id' => $this->user->id,
            'completed' => 0,
            'scheduled_completion' => "2023-08-02"
        ]);

        $this->actingAs($this->user)
            ->delete(route('habit.delete', ['habit' => $this->habit]))
            ->assertRedirect(route('habit'));

        $this->assertDatabaseHas('habits', [
            'id' => $this->habit->id,
            'deleted_at' => now()
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit1->id,
            'deleted_at' => null
        ]);

        $this->assertDatabaseHas('habit_schedules', [
            'id' => $scheduledHabit2->id,
            'deleted_at' => null
        ]);
    }
}
