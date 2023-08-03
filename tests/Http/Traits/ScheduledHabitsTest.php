<?php

namespace Tests\Http\Traits;

use Tests\TestCase;
use App\Models\User;
use Tests\Traits\RefreshDatabase;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduledHabitsTest extends TestCase
{
    use RefreshDatabase;
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
        $this->markTestSkipped();
    }

    /** @test */
    public function gets_users_scheduled_habits_on_monday_and_sunday()
    {
        $this->markTestSkipped();
    }
}
