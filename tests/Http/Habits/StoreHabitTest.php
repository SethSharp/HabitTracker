<?php

namespace Tests\Http\Habits;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreHabitTest extends TestCase
{
    use DatabaseMigrations;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function must_be_authenticated()
    {
        $this->post(route("habit.store"))
            ->assertRedirect('/login');

        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function fields_are_required(): void
    {
        $habitData = [
            'name' => '',
            'description' => '',
            'frequency' => null,
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasErrors([
                'name',
                'description',
                'frequency',
            ]);
    }

    /** @test */
    public function daily_config_is_required_if_frequency_is_daily(): void
    {
        $habitData = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 0,
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasErrors(['daily_config'])
            ->assertSessionDoesntHaveErrors(['weekly_config', 'monthly_config']);
    }

    /** @test */
    public function weekly_config_is_required_if_frequency_is_weekly(): void
    {
        $habitData = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 1,
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasErrors(['weekly_config'])
            ->assertSessionDoesntHaveErrors(['daily_config', 'monthly_config']);
    }

    /** @test */
    public function monthly_config_is_required_if_frequency_is_monthly(): void
    {
        $habitData = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasErrors(['monthly_config'])
            ->assertSessionDoesntHaveErrors(['daily_config', 'weekly_config']);
    }

    /** @test */
    public function habit_can_be_created()
    {
        $habitData = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17'
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertRedirect();

        $this->assertDatabaseHas('habits', [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 'monthly',
        ]);
    }
}
