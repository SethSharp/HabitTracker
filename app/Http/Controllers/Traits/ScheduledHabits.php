<?php

namespace App\Http\Controllers\Traits;

use App\Models\User;
use DateTime;
use Illuminate\Support\Collection;

trait ScheduledHabits
{
    use DateHelper;

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
        $week = $this->getWeekDatesStartingFromMonday($this->getMonday());

        $thisWeeksHabits = $user->scheduledHabits()
            ->where('scheduled_completion', '>=', $this->getMonday() ?? $nextMonday)
            ->where('scheduled_completion', '<=', $this->getSunday() ?? $nextSunday)
            ->with('habit')
            ->get();


        return $week->reduce(function (Collection $carry, string $date, int $key) use ($thisWeeksHabits) {
            $carry[$key] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();

            return $carry;
        }, collect());
    }
}
