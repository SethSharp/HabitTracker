<?php

namespace Tests\Console\Commands;

use Tests\TestCase;

class ScheduleHabits extends TestCase
{
    /** @test */
    public function command_is_called_successfully()
    {
        $this->artisan('app:schedule-habits')
            ->assertSuccessful();
    }
}
