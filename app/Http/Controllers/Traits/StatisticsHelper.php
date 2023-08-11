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
    public function getMonthlyHabitScheduleWithHabits(User $user): array
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
