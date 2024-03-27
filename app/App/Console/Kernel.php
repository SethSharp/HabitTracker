<?php

namespace App\App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\App\Console\Commands\Counters\Testing;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // test
        $schedule->command(Testing::class)->everyFiveMinutes();
        // scheduling habits
        //        $schedule->command(ScheduleHabitsForMonth::class)->monthly();

        // counters
        //        $schedule->command(HabitStreak::class)->daily();
        //        $schedule->command(UserStreak::class)->daily();

        // Notifications
        //        $schedule->command(SendDailyHabitReminder::class)->dailyAt('08:00');
        //        $schedule->command(SendHabitGoalReminder::class)->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
