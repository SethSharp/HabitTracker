<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Counters\UserStreak;
use App\Console\Commands\Counters\HabitStreak;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\Notifications\SendHabitGoalReminder;
use App\Console\Commands\Notifications\SendDailyHabitReminder;
use App\Console\Commands\ScheduledHabits\ScheduleHabitsForMonth;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // scheduling habits
        $schedule->command(ScheduleHabitsForMonth::class)->mondays();

        // counters
        $schedule->command(HabitStreak::class)->daily();
        $schedule->command(UserStreak::class)->daily();

        // Notifications
        $schedule->command(SendDailyHabitReminder::class)->dailyAt('08:00');
        $schedule->command(SendHabitGoalReminder::class)->dailyAt('08:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
