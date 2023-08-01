<?php

namespace Tests\Console\Commands;

use Tests\TestCase;

class SendHabitScheduleReminderTest extends TestCase
{
    /** @test */
    public function command_is_called_successfully()
    {
        $this->artisan('habits:send-habit-reminder')
            ->assertSuccessful();
    }
}
