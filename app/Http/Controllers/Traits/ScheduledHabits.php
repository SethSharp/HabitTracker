<?php

namespace App\Http\Controllers\Traits;

use App\Http\CacheKeys;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait ScheduledHabits
{
    use DateHelper;

    public function getDailyScheduledHabits(User $user): array
    {
            return $user->scheduledHabits()
            ->where('scheduled_completion', '=', date('Y-m-d'))
            ->with('habit')
            ->get()
            ->toArray();
    }

    public function getWeeklyScheduledHabits(User $user, string $nextSunday = null, string $nextMonday = null): Collection
    {
        $week = $this->getWeekDatesStartingFromMonday($this->getMonday());

        return Cache::remember(CacheKeys::weeklyScheduledHabits($user), now()->addHour(), function() use ($user, $nextSunday, $nextMonday, $week) {
            $thisWeeksHabits = $user->scheduledHabits()
                ->where('scheduled_completion', '>=', $this->getMonday() ?? $nextMonday)
                ->where('scheduled_completion', '<=', $this->getSunday() ?? $nextSunday)
                ->with('habit')
                ->get();


            return $week->reduce(function (Collection $carry, string $date, int $key) use ($thisWeeksHabits) {
                $carry[$key] = $thisWeeksHabits->filter(fn ($habit) => $habit->scheduled_completion === $date)->toArray();

                return $carry;
            }, collect());
        });
    }
}
