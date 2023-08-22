<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use App\Http\CacheKeys;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait HabitLog
{
    public function getHabitLog(User $user, string $start_date, string $end_date)
    {
        $habitLogs = $user->scheduledHabits()
            ->where('scheduled_completion', '>=', $start_date)
            ->where('scheduled_completion', '<=', $end_date)
            ->with('habit')
            ->get();

        $habitLogsGrouped = $habitLogs->groupBy(fn ($item) => $item->habit?->id);

        $habitIds = $user->habits()->pluck('id');

        return $habitIds->reduce(function (Collection $carry, string $id) use ($habitLogsGrouped) {
            $carry[$id] = $habitLogsGrouped->get($id, []);
            return $carry;
        }, collect());
    }

    public function getWeeklyLog(User $user, string $start_date, string $end_date): Collection
    {
        return Cache::remember(
            CacheKeys::weeklyHabitLog($user),
            now()->addDay(),
            fn () => $user->scheduledHabits()
                ->where('scheduled_completion', '>=', $start_date)
                ->where('scheduled_completion', '<=', $end_date)
                ->with(['habit' => fn ($query) => $query->withTrashed()])
                ->orderBy('scheduled_completion', 'desc')
                ->get()
        );
    }
}
