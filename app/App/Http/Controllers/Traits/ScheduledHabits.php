<?php

namespace App\App\Http\Controllers\Traits;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Domain\Iam\Models\User;
use Illuminate\Support\Collection;

trait ScheduledHabits
{
    public function getDailyScheduledHabits(User $user, ?string $date = null): array
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', is_null($date) ? Carbon::now()->toDateString() : $date)
            ->with('habit')
            ->orderBy('completed', 'asc')
            ->get()
            ->toArray();
    }

    public function getWeeklyScheduledHabits(User $user): Collection
    {
        $week = $this->getWeekDatesStartingFromMonday();

        $thisWeeksHabits = $user->scheduledHabits()
            ->orderBy('completed', 'asc')
            ->where('scheduled_completion', '>=', Carbon::now()->startOfWeek(0)->toDateString())
            ->where('scheduled_completion', '<=', Carbon::now()->endOfWeek(-1)->toDateString())
            ->with(['habit' => fn ($query) => $query->withTrashed()])
            ->get();

        return $week->reduce(function (Collection $carry, string $date) use ($thisWeeksHabits) {
            $carry[$date] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();
            return $carry;
        }, collect());
    }

    public function getHabitsForRange(User $user, string $startDate): Collection
    {
        $week = $this->getWeekDatesFromDate($startDate);

        $start = Carbon::parse($startDate);

        $thisWeeksHabits = $user->scheduledHabits()
            ->orderBy('completed')
            ->where('scheduled_completion', '>=', $start->toDateString())
            ->where('scheduled_completion', '<=', $start->addDays(7)->toDateString())
            ->with(['habit' => fn ($query) => $query->withTrashed()])
            ->get();

        return $week->reduce(function (Collection $carry, string $date) use ($thisWeeksHabits) {
            $carry[$date] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();
            return $carry;
        }, collect());
    }

    public function monthlyScheduledHabits(User $user, string|null $month): array
    {
        if (is_null($month)) {
            $month = Carbon::now()->monthName;
        }

        $startDate = Carbon::parse("1 $month")->startOfMonth();
        $endDate = Carbon::parse("1 $month")->endOfMonth();

        if ($startDate > Carbon::now()) {
            return [];
        }

        return $this->getMonthlyHabitsByDate($user, $startDate, $endDate);
    }

    protected function getMonthlyHabitsByDate(User $user, Carbon $startDate, Carbon $endDate): array
    {
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

            foreach ($habits as $scheduledHabit) {
                if ($scheduledHabit->scheduled_completion === $formattedDate) {
                    $habitsForDate->push($scheduledHabit);
                }
            }

            $habitByDate[$index] = $habitsForDate;
            $index++;
        }

        return $habitByDate;
    }

    public function getHabitFiltersForMonth(User $user, ?string $month): Collection
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
            if (! $habits->contains('id', $scheduledHabit->habit_id)) {
                $habits->push([
                    'id' => $scheduledHabit->habit->id,
                    'title' => $scheduledHabit->habit->name,
                    'attributePath' => 'habit.id',
                    'filterBy' => $scheduledHabit->habit->id,
                    'colour' => $scheduledHabit->habit->colour,
                ]);
            }
        }

        return $habits;
    }

    private function getWeekDatesStartingFromMonday(): Collection
    {
        $dates = collect();

        $startOfWeek = Carbon::now()->startOfWeek(0);

        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->toDateString();
            $startOfWeek->addDay();
        }

        return $dates;
    }

    private function getWeekDatesFromDate(string $date): Collection
    {
        $dates = collect();

        $startOfWeek = Carbon::parse($date);

        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->toDateString();
            $startOfWeek->addDay();
        }

        return $dates;
    }
}
