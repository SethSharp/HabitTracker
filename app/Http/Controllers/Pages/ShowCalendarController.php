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
        return Inertia::render('Calendar', [
            'habitsByDay' => $this->getMonthlyHabitScheduleWithHabits($request->user(), $request->route('month')),
            'habits' => $request->user()->habits()->withTrashed()->get(),
            'month' => $request->route('month') ?: Carbon::now()->monthName,
            'habitFilters' => $this->getHabitsScheduledWithinMonth($request->user(), $request->route('month'))
        ]);
    }
}
