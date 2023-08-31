<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScheduledHabits;

class ShowCalendarController extends Controller
{
    use ScheduledHabits;

    public function __invoke(Request $request): Response
    {
        $month = $request->route('month') ?: Carbon::now()->monthName;

        return Inertia::render('Calendar', [
            'habitsByDay' => $this->getMonthlyScheduledHabits($request->user(), $month),
            'month' => $month,
            'habitFilters' => $this->getHabitFiltersForMonth($request->user(), $month)
        ]);
    }
}
