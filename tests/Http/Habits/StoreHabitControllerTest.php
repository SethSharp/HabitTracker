<?php

namespace Tests\Http\Habits;

use Carbon\Carbon;
use Tests\TestCase;
use App\Enums\Goals;
use App\Models\User;
use App\Models\Habit;
use Tests\Traits\RefreshDatabase;

class StoreHabitControllerTest extends TestCase
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
            'daily_config' => '[2,3,4]',
            'start_next_week' => false,
            'colour' => '#00cedf',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->weeklyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 1,
            'weekly_config' => 4,
            'start_next_week' => false,
            'colour' => '#00cedf',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->monthlyArray = [
            'name' => 'Testing name',
            'description' => 'Testing description',
            'frequency' => 2,
            'monthly_config' => '2023-7-17',
            'start_next_week' => false,
            'colour' => '#00cedf',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];
    }

    /** @test */
    public function must_be_authenticated()
    {
        $this->post(route("habit.store"))
            ->assertRedirect('/login');
    }

    /** @test */
    public function fields_are_required(): void
    {
        $habitData = [
            'name' => '',
            'description' => '',
            'frequency' => null,
            'colour'
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasErrors([
                'name',
                'description',
                'frequency',
                'colour'
            ]);
    }

    /** @test */
    public function daily_config_is_required_if_daily_is_selected()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 0,
            'colour' => 'colour'
        ];

        $this->actingAs($this->user)
            ->post(route('habit.store'), $habitData)
            ->assertSessionHasErrors(['daily_config']);
    }

    /** @test */
    public function weekly_config_is_required_if_weekly_is_selected()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 1,
            'colour' => 'colour'
        ];

        $this->actingAs($this->user)
            ->post(route('habit.store'), $habitData)
            ->assertSessionHasErrors(['weekly_config']);
    }

    /** @test */
    public function monthly_config_is_required_if_monthly_is_selected()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 2,
            'colour' => 'colour'
        ];

        $this->actingAs($this->user)
            ->post(route('habit.store'), $habitData)
            ->assertSessionHasErrors(['monthly_config']);
    }

    /** @test */
    public function can_store_a_daily_habit()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 0,
            'daily_config' => [1,2,3],
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('["1", "2", "3"]', $habit->occurrence_days);
    }

    /** @test */
    public function can_store_a_weekly_habit()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 1,
            'weekly_config' => '2',
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('[2]', $habit->occurrence_days);
    }

    /** @test */
    public function can_store_a_monthly_habit()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 2,
            'monthly_config' => '2023-08-13',
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('["2023-08-13"]', $habit->occurrence_days);
    }

    /** @test */
    public function starting_habits_next_week_start_scheduled_habits_next_week()
    {
        Carbon::setTestNow("2023-08-01");

        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 0,
            'daily_config' => [1,2,3],
            'colour' => 'colour',
            'start_next_week' => true,
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('["1", "2", "3"]', $habit->occurrence_days);

        $this->assertDatabaseCount('habit_schedules', 12);
    }

    /** @test */
    public function habits_are_scheduled_for_half_the_month()
    {
        Carbon::setTestNow("2023-08-21");

        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 0,
            'daily_config' => [1,2,3],
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('["1", "2", "3"]', $habit->occurrence_days);

        $this->assertDatabaseCount('habit_schedules', 6);
    }

    /** @test */
    public function daily_habits_are_scheduled_for_the_whole_month()
    {
        Carbon::setTestNow("2023-08-01");

        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 0,
            'daily_config' => [1,2,3],
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour,
            'scheduled_to' => null
        ]);

        $this->assertEquals('["1", "2", "3"]', $habit->occurrence_days);

        $this->assertDatabaseCount('habit_schedules', 14);
    }

    /** @test */
    public function weekly_habits_are_scheduled_on_the_correct_date()
    {
        Carbon::setTestNow("2023-08-01");

        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 1,
            'weekly_config' => '2',
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour
        ]);

        $this->assertEquals('[2]', $habit->occurrence_days);

        $this->assertDatabaseCount('habit_schedules', 5);
    }

    /** @test */
    public function monthly_habits_are_scheduled_on_the_correct_date()
    {
        $habitData = [
            'name' => 'Habit 1',
            'description' => 'Habit description',
            'frequency' => 2,
            'monthly_config' => '2023-08-13',
            'colour' => 'colour',
            'scheduled_to' => [
                'length' => Goals::NONE->value,
                'time' => 0,
            ]
        ];

        $this->actingAs($this->user)
            ->post(route("habit.store", $habitData))
            ->assertSessionHasNoErrors();

        $habit = Habit::all()->first();

        $this->assertDatabaseHas('habits', [
            'id' => $habit->id,
            'name' => $habit->name,
            'description' => $habit->description,
            'colour' => $habit->colour
        ]);

        $this->assertEquals('["2023-08-13"]', $habit->occurrence_days);

        $this->assertDatabaseCount('habit_schedules', 1);
    }
}
