<?php

namespace App\Http\Controllers\Pages;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\StatisticsHelper;

class ShowStatisticsController extends Controller
{
    use StatisticsHelper;

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Statistics', [
            'habitsByDay' => $this->getMonthlyHabitScheduleWithHabits(Auth::user(), $request->route('month')),
            'habits' => Auth::user()->habits()->get(),
            'month' => $request->route('month') ?: Carbon::now()->monthName,
            'habitFilters' => $this->getHabitsScheduledWithinMonth(Auth::user(), $request->route('month'))
        ]);
    }
}
