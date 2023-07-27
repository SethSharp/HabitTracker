<?php

namespace App\Http\Controllers\Traits;

use DateTime;
use DateInterval;
use Illuminate\Support\Collection;

trait DateHelper
{
    public function getSunday(): string
    {
        $currentDate = new DateTime();

        $daysUntilNextSunday = 7 - $currentDate->format('w');

        $next = $currentDate->modify("+$daysUntilNextSunday days");

        return $next->format('Y-m-d');
    }

    public function getMonday(): string
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

    public function getWeekDatesStartingFromMonday($startDate): Collection
    {
        $dates = collect();

        $startOfWeek = strtotime($this->getMonday(), strtotime($startDate));

        for ($i = 0; $i < 7; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $startOfWeek));
        }

        return $dates;
    }

    public function getDateXDaysAgo(int $x): string
    {
        $today = new DateTime();

        $xDaysAgo = $today->sub(new DateInterval('P' . $x . 'D'));

        return $xDaysAgo->format('Y-m-d');
    }
}
