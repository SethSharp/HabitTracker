<?php

namespace Tests\Console\Commands;

use Tests\TestCase;
use App\Models\User;
use App\Models\Habit;
use Tests\Traits\RefreshDatabase;

class ScheduleHabitsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function command_is_called_successfully()
    {
        $user = User::factory()->create();

        $habits = Habit::factory()->count(3)->create([
            'user_id' => $user->id
        ]);

        $this->artisan('habits:schedule-habits')
            ->assertSuccessful();
    }
}
