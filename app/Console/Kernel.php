<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use App\Console\Commands\Habits\ScheduleHabits;
use App\Console\Commands\Habits\SendHabitScheduleReminder;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command(ScheduleHabits::class)->mondays();
        $schedule->command(SendHabitScheduleReminder::class)->dailyAt('17:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
