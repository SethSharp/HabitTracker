<?php

namespace App\Console\Commands\Testing;

use App\Models\User;
use App\Mail\HabitReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Traits\ScheduledHabits;

class SendDailyReminder extends Command
{
    use ScheduledHabits;
    protected $signature = 'testing:daily-reminder';
    protected $description = 'Send daily reminder email';

    public function handle()
    {
        $user = User::all()->first();
        if (! is_null($user->email_verified_at)) {
            Mail::to($user->email)->send(new HabitReminder($user->name));
        }
    }
}
