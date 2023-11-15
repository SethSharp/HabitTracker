<?php

namespace App\Console\Commands\Counters;

use App\Domain\Iam\Models\User;
use Illuminate\Console\Command;
use App\Http\Controllers\Traits\ScheduledHabits;

class Testing extends Command
{
    use ScheduledHabits;
    protected $signature = 'counters:testing';
    protected $description = '';

    public function handle()
    {
        $user = User::first();

        $user->increment('streak');
    }
}
