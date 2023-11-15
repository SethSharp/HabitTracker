<?php

namespace Tests\Http\Habits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Habit;
use App\Domain\Iam\Models\User;
use Tests\Traits\RefreshDatabase;

class UpdateHabitControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected array $dailyArray;
    protected array $weeklyArray;
    protected array $monthlyArray;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->dailyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 0,
            'daily_config' => [1,2,3],
            'colour' => '#00cedf'
        ];

        $this->weeklyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 1,
            'weekly_config' => 4,
            'colour' => '#00cedf'
        ];

        $this->monthlyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17',
            'colour' => '#00cedf'
        ];
    }

    /** @test */
    public function must_be_authenticated()
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->post(route("habit.update", $habit), $this->dailyArray)
            ->assertRedirect('/login');
    }

    /** @test */
    public function return_unauthorized_if_habit_belongs_to_other_user()
    {
        $user = User::factory()->create();
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->actingAs($user)
            ->post(route("habit.update", $habit), $this->dailyArray)
            ->assertStatus(403);
    }

    /** @test */
    public function fields_are_required(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $habitData = [
            'name' => '',
            'frequency' => null,
            'colour' => null,
        ];

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $habitData)
            ->assertSessionHasErrors([
                'name',
                'frequency',
                'colour'
            ]);
    }

    /** @test */
    public function cannot_update_habit_goal_if_scheduled_to_is_set()
    {
        Carbon::setTestNow("2023-01-01");

        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'scheduled_to' => "2023-01-01"
        ]);

        $monthlyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17',
            'colour' => '#00cedf',
            'scheduled_to' => [
                'length' => 2,
                'time' => 12,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $monthlyArray)
            ->assertSessionDoesntHaveErrors(['daily_config', 'weekly_config']);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'user_id' => $this->user->id,
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 'monthly',
            'scheduled_to' => "2023-01-01"
        ]);
    }

    /** @test */
    public function can_update_to_daily_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $this->dailyArray)
            ->assertSessionDoesntHaveErrors(['weekly_config', 'monthly_config']);

        $updatedHabit = Habit::find($habit->id);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'user_id' => $this->user->id,
            'name' => $updatedHabit->name,
            'description' => $updatedHabit->description,
            'frequency' => 'Daily',
        ]);

        $this->assertEquals($updatedHabit->occurrence_days, '[2, 3, 4]');
    }

    /** @test */
    public function can_update_weekly_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'frequency' => 'Weekly',
            'occurrence_days' => '[2]'
        ]);

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $this->weeklyArray)
            ->assertSessionDoesntHaveErrors(['daily_config', 'monthly_config']);

        $updatedHabit = Habit::find($habit->id);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'user_id' => $this->user->id,
            'name' => $updatedHabit->name,
            'description' => $updatedHabit->description,
            'frequency' => 'Weekly',
        ]);

        $this->assertEquals($updatedHabit->occurrence_days, '[2]');
    }

    /** @test */
    public function can_update_to_monthly_habit(): void
    {
        $habit = Habit::factory()->create([
            'user_id' => $this->user->id,
            'frequency' => 'Monthly',
            'occurrence_days' => json_encode(['2023-04-09'])
        ]);

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $this->monthlyArray)
            ->assertSessionDoesntHaveErrors(['daily_config', 'weekly_config']);

        $updatedHabit = Habit::find($habit->id);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'user_id' => $this->user->id,
            'name' => $updatedHabit->name,
            'description' => $updatedHabit->description,
            'frequency' => 'Monthly',
        ]);

        $this->assertEquals($updatedHabit->occurrence_days, '["2023-04-09"]');
    }

    /** @test */
    public function can_set_habit_goal_if_scheduled_to_is_null()
    {
        Carbon::setTestNow("2023-01-01");

        $habit = Habit::factory()->create([
            'user_id' => $this->user->id
        ]);

        $monthlyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17',
            'colour' => '#00cedf',
            'scheduled_to' => [
                'length' => 2,
                'time' => 2,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.update", $habit), $monthlyArray)
            ->assertSessionDoesntHaveErrors(['daily_config', 'weekly_config'])
            ->assertSessionHasNoErrors();

        $updatedHabit = Habit::find($habit->id);

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'user_id' => $this->user->id,
            'name' => $updatedHabit->name,
            'description' => $updatedHabit->description,
            'frequency' => 'monthly',
            'scheduled_to' => "2023-03-01"
        ]);
    }
}
