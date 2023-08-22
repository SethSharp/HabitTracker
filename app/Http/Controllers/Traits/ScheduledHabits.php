<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;

trait ScheduledHabits
{
    use DateHelper;

    public function getDailyScheduledHabits(User $user): array
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', '=', Carbon::now()->toDateString())
            ->where('completed', '=', 0)
            ->with('habit')
            ->get()
            ->toArray();
    }

    public function getCompletedDailyHabits(user $user): array
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', '=', Carbon::now()->toDateString())
            ->where('completed', '=', 1)
            ->with('habit')
            ->get()
            ->toArray();
    }

    public function getWeeklyScheduledHabits(User $user): Collection
    {
        $week = $this->getWeekDatesStartingFromMonday();

        $thisWeeksHabits = $user->scheduledHabits()
            ->where('scheduled_completion', '>=', Carbon::now()->startOfWeek()->toDateString())
            ->where('scheduled_completion', '<=', Carbon::now()->endOfWeek()->toDateString())
            ->with(['habit' => fn ($query) => $query->withTrashed()])
            ->get();

        return $week->reduce(function (Collection $carry, string $date, int $key) use ($thisWeeksHabits) {
            $carry[$key] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();
            return $carry;
        }, collect());
    }

    public function getMonthlyHabitScheduleWithHabits(User $user, ?string $month): array
    {
        if (is_null($month)) {
            $month = Carbon::now()->monthName;
        }

        // TODO: Caching with monthlyHabitLog... maybe (data for 12 months per user) (N * 12 * X) in cache
        $startDate = Carbon::parse("1 $month")->startOfMonth();
        $endDate = Carbon::parse("1 $month")->endOfMonth();

        $habits = $user->scheduledHabits()
            ->whereBetween('scheduled_completion', [$startDate, $endDate])
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

    public function getHabitsScheduledWithinMonth(User $user, ?string $month): Collection
    {
        if (is_null($month)) {
            $month = Carbon::now()->monthName;
        }

        $startDate = Carbon::parse("1 $month")->startOfMonth();
        $endDate = Carbon::parse("1 $month")->endOfMonth();

        $data = $user->scheduledHabits()
            ->with('habit')
            ->whereBetween('scheduled_completion', [$startDate, $endDate])
            ->get();

        $habits = collect();

        foreach ($data as $scheduledHabit) {
            if (! $habits->contains('id', $scheduledHabit->habit->id)) {
                $habits->push($scheduledHabit->habit);
            }
        }

        ray($habits);

        return $habits;
    }
}
