<?php

namespace App\Http\Controllers\Traits;

use App\Models\Habit;
use App\Models\User;
use DateTime;
use Illuminate\Support\Collection;

trait ScheduledHabits
{
    public function getScheduledHabitsForUser(User $user): Collection
    {
        return $user->scheduledHabits()
            ->where('scheduled_completion', '<=', $this->getNextSunday())
            ->where('scheduled_completion', '>=', $this->getPreviousMonday())
            ->with('habit')
            ->get();
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
}
