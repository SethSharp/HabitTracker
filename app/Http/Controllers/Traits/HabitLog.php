<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use Illuminate\Support\Collection;

trait HabitLog
{
    public function getHabitLog()
    {

    }

    public function getWeeklyLog(User $user, string $start_date, string $end_date): Collection
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', '>=', $start_date)
            ->where('scheduled_completion', '<=', $end_date)
            ->with('habit')
            ->get();
    }
}
