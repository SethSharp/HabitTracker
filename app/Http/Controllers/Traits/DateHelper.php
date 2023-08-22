<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait DateHelper
{
    public function getWeekDatesStartingFromMonday($startDate): Collection
    {
        $dates = collect();

        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();

        for ($i = 0; $i < 7; $i++) {
            $dates[] = date('Y-m-d', strtotime("+$i days", $startOfWeek));
        }

        return $dates;
    }
}
