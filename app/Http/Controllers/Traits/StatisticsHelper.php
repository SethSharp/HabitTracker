<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

trait StatisticsHelper
{
    // Combine this with another trait
    // Also make the traits more generalised
    // Functions that grab a range, CollectionRangeHelper etc
    public function getMonthlyHabitScheduleWithHabits(User $user): Collection
    {
        // TODO: Caching with monthlyHabitLog
        $startDate = Carbon::now()->startOfMonth();
        $endDate = Carbon::now()->endOfMonth();

        $habits = $user->scheduledHabits()
                ->withTrashed()
                ->where('scheduled_completion', '>=', $startDate)
                ->where('scheduled_completion', '<=', $endDate)
                ->with(['habit' => fn ($query) => $query->withTrashed()])
                ->orderBy('scheduled_completion', 'desc')
                ->get();

        $period = CarbonPeriod::create($startDate, $endDate);

        $dateCollection = collect([]);

        foreach ($period as $date) {
            $dateCollection->push($date->toDateString());
        }

        return $dateCollection->reduce(function (Collection $carry, string $date, int $key) use ($habits) {
            $carry[$date] = $habits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();

            return $carry;
        }, collect());
    }
}
