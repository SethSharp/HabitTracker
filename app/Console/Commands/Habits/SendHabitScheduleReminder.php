<?php

namespace App\Console\Commands\Habits;

use Illuminate\Console\Command;

class SendHabitScheduleReminder extends Command
{
    protected $signature = 'habits:send-habit-reminder';
    protected $description = 'Emails to users about their habits for the day';

    public function handle()
    {
    }
}
