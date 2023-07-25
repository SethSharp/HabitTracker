<?php

namespace App\Http\Controllers\Traits;

use App\Http\CacheKeys;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HabitLog
{
    public function getHabitLog()
    {
        // TODO
    }

    public function getWeeklyLog(User $user, string $start_date, string $end_date): Collection
    {
        return Cache::remember(CacheKeys::weeklyHabitLog($user), now()->addDay(),
            fn () => $user->scheduledHabits()
                ->where('scheduled_completion', '>=', $start_date)
                ->where('scheduled_completion', '<=', $end_date)
                ->with('habit')
                ->get());
    }
}
