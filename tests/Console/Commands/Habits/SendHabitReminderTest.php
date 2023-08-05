<?php

namespace Tests\Console\Commands\Habits;

use App\Models\EmailPreferences;
use App\Models\User;
use App\Notifications\DailyReminderNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Tests\Traits\RefreshDatabase;

class SendHabitReminderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function notification_is_sent_when_command_is_called()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => now()
        ]);

        EmailPreferences::factory()->create([
            'user_id' => $user->id,
            'daily_reminder' => true
        ]);

        $this->artisan('habits:send-habit-reminder')
            ->assertSuccessful();

        Notification::assertSentTo($user, DailyReminderNotification::class);
    }

    /** @test */
    public function notification_is_not_sent_if_email_is_not_verified()
    {
        Notification::fake();

        User::factory()->create([
            'email_verified_at' => null
        ]);

        $this->artisan('habits:send-habit-reminder')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }

    /** @test */
    public function mail_is_not_sent_if_mail_preference_is_false()
    {
        Notification::fake();

        $user = User::factory()->create([
            'email_verified_at' => null
        ]);

        EmailPreferences::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->artisan('habits:send-habit-reminder')
            ->assertSuccessful();

        Notification::assertNothingSent();
    }
}
