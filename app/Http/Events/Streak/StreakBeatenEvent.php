<?php

namespace App\Http\Events\Streak;

use App\Models\Habit;
use Illuminate\Support\Facades\Event;

class StreakBeatenEvent extends Event
{
    public Habit $habit;

    public function __construct(Habit $habit)
    {
        $this->habit = $habit;
    }
}
