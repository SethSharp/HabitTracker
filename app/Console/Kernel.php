<?php

namespace App\Console;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Cleanup\HabitsTable;
use App\Console\Commands\Counters\HabitStreak;
use App\Console\Commands\Counters\WeeklyStreak;
use App\Console\Commands\Cleanup\ScheduledHabitsTable;
use App\Console\Commands\Habits\SendDailyHabitReminder;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ScheduledHabits\ScheduleHabitsForWeek;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // scheduling habits
        $schedule->command(ScheduleHabitsForWeek::class)->mondays();

        // counters
        $schedule->command(HabitStreak::class)->daily();
        $schedule->command(WeeklyStreak::class)->daily();

        // Cleanup
        $schedule->command(ScheduledHabitsTable::class)
            ->mondays()
            ->after(function () {
                // This closure will be executed after all other commands for the day
                Artisan::call(ScheduledHabitsTable::class);
            });

        $schedule->command(HabitsTable::class)
            ->mondays()
            ->after(function () {
                // This closure will be executed after all other commands for the day
                Artisan::call(HabitsTable::class);
            });

        // Notifications
        $schedule->command(SendDailyHabitReminder::class)->dailyAt('17:00');
        // TODO: Start of the week notification
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
