<?php

namespace App\Console\Commands\Testing;

use App\Models\User;
use Illuminate\Console\Command;
use App\Notifications\RegistrationNotification;
use App\Http\Controllers\Traits\ScheduledHabits;

class SendRegistration extends Command
{
    use ScheduledHabits;

    protected $signature = 'testing:registration';
    protected $description = '';

    public function handle()
    {
        $user = User::all()->first();
        if (! is_null($user->email_verified_at)) {
            $user->notify(new RegistrationNotification());
        }
    }
}
