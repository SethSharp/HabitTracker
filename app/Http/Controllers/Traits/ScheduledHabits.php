<?php

namespace App\Http\Controllers\Traits;

use App\Models\Habit;
use App\Models\User;
use DateTime;
use Illuminate\Support\Collection;

trait ScheduledHabits
{

    public function getDailyScheduledHabits(User $user)
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', '=', date('Y-m-d'))
            ->with('habit')
            ->get()
            ->toArray();
    }

    public function getWeeklyScheduledHabits(User $user, string $nextSunday = null, string $nextMonday = null): Collection
    {
        // TODO: Cache, create Cache keys file etc..
        ray(date('Y-m-d'));
        $week = $this->getWeekDatesStartingFromMonday(date('Y-m-d'));
        ray($week);

        $thisWeeksHabits = $user->scheduledHabits()
            ->where('scheduled_completion', '>=', $this->getPreviousMonday() ?? $nextMonday)
            ->where('scheduled_completion', '<=', $this->getNextSunday() ?? $nextSunday)
            ->with('habit')
            ->get();

        $test = $week->reduce(function (Collection $carry, string $date, int $key) use ($thisWeeksHabits) {
            $carry[$key] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();

            return $carry;
        }, collect());

        ray($test);

        return $test;
    }

    private function getNextSunday(): string
    {
        $currentDate = new DateTime();

        $daysUntilNextSunday = 7 - $currentDate->format('w');

        $next = $currentDate->modify("+$daysUntilNextSunday days");

        return $next->format('Y-m-d');
    }

    private function getPreviousMonday(): string
    {
        // Create a DateTime object for the current date
        $currentDate = new DateTime();

        // Calculate the day of the week (0 - Sunday, 1 - Monday, ..., 6 - Saturday)
        $currentDayOfWeek = $currentDate->format('w');

        // Calculate the number of days to subtract to reach the previous Monday
        $daysToPreviousMonday = $currentDayOfWeek == 0 ? 6 : $currentDayOfWeek - 1;

        // Subtract the calculated days from the current date to get the previous Monday date
        $previousMonday = $currentDate->modify("-$daysToPreviousMonday days");

        // Return the previous Monday date in the format 'Y-m-d' (e.g., '2023-07-17')
        return $previousMonday->format('Y-m-d');
    }

    private function getWeekDatesStartingFromMonday($startDate): Collection
    {
        $dates = collect();

        // Convert the input date to a timestamp
        $timestamp = strtotime($startDate);

        // Calculate the timestamp of the first Monday on or before the input date
        $startOfWeek = strtotime('last Monday', $timestamp);
        ray($startOfWeek);

        // Loop through the days of the week and add them to the $dates array
        for ($i = 0; $i < 7; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $startOfWeek));
        }

        return $dates;
    }
}
