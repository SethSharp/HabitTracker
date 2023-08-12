<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;

trait StatisticsHelper
{
    // TODO: Combine this with another trait
    // Also make the traits more generalised
    // Functions that grab a range, CollectionRangeHelper etc
    public function getMonthlyHabitScheduleWithHabits(User $user, ?string $month): array
    {
        if (is_null($month)) {
            $month = Carbon::now()->monthName;
        }

        // TODO: Caching with monthlyHabitLog... maybe (data for 12 months per user) (N * 12 * X) in cache
        $startDate = Carbon::parse("1 $month")->startOfMonth();
        $endDate = Carbon::parse("1 $month")->endOfMonth();

        $habits = $user->scheduledHabits()
            ->withTrashed()
            ->where('scheduled_completion', '>=', $startDate)
            ->where('scheduled_completion', '<=', $endDate)
            ->with(['habit' => fn ($query) => $query->withTrashed()])
            ->orderBy('scheduled_completion', 'desc')
            ->get();

        $period = CarbonPeriod::create($startDate, $endDate);

        $habitByDate = [];
        $index = 0;
        foreach ($period as $date) {
            $formattedDate = $date->toDateString();

            $habitsForDate = collect();

            foreach ($habits as $habit) {
                if ($habit->scheduled_completion === $formattedDate) {
                    $habitsForDate->push($habit);
                }
            }

            $habitByDate[$index] = $habitsForDate;
            $index++;
        }

        return $habitByDate;
    }
}
