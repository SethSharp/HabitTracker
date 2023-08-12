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
        $month = $request->route('month');
        return Inertia::render('Statistics', [
            'habitsByDay' => $this->getMonthlyHabitScheduleWithHabits(auth()->user(), $month),
            'habits' => Auth::user()->habits()->get(),
            'month' => $month ?: Carbon::now()->monthName,
        ]);
    }
}
