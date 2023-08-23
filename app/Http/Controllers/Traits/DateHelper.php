<?php

namespace App\Http\Controllers\Traits;

use Carbon\Carbon;
use Illuminate\Support\Collection;

trait DateHelper
{
    public function getWeekDatesStartingFromMonday(): Collection
    {
        $dates = collect();

        $startOfWeek = Carbon::now()->startOfWeek(0);

        for ($i = 0; $i < 7; $i++) {
            $dates[] = $startOfWeek->toDateString();
            $startOfWeek->addDay();
        }

        return $dates;
    }
}
