<?php

namespace Tests\Http\Habits;

use App\Models\Habit;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class StoreHabitControllerTest extends TestCase
{
    use DatabaseMigrations;

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
            'daily_config' => '[1,2,3]'
        ];

        $this->weeklyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 1,
            'weekly_config' => 4
        ];

        $this->monthlyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17'
        ];
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
        $this->actingAs($this->user)
            ->post(route("habit.store", $this->dailyArray))
            ->assertSessionDoesntHaveErrors(['weekly_config', 'monthly_config']);

        $habit = Habit::all()->first();
        $this->assertEquals($habit->occurrence_days, '"[1,2,3]"');
    }

    /** @test */
    public function weekly_config_is_required_if_frequency_is_weekly(): void
    {
        $this->actingAs($this->user)
            ->post(route("habit.store", $this->weeklyArray))
            ->assertSessionDoesntHaveErrors(['daily_config', 'monthly_config']);

        $habit = Habit::all()->first();
        $this->assertEquals($habit->occurrence_days, '[4]');
    }

    /** @test */
    public function monthly_config_is_required_if_frequency_is_monthly(): void
    {
        $this->actingAs($this->user)
            ->post(route("habit.store", $this->monthlyArray))
            ->assertSessionDoesntHaveErrors(['daily_config', 'weekly_config']);

        $habit = Habit::all()->first();
        $this->assertEquals($habit->occurrence_days, '["2023-07-17"]');
    }

    /** @test */
    public function user_id_is_correctly_stored()
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
            'user_id' => $this->user->id,
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 'monthly',
        ]);
    }
}
