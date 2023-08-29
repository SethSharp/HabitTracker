<?php

namespace App\Http\Events\Habits;

use App\Enums\Log;
use App\Models\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

class HabitCompletedEvent extends Event
{
    public function __construct(Habit $habit)
    {
        HabitLog::create([
            'user_id' => $habit->user_id,
            'habit_id' => $habit->id,
            'log_description' => $habit->name .  ' habit completed on ' . Carbon::now()->toDateString() . '.',
            'log_type' => Log::HABIT_COMPLETED,
        ]);
    }
}
