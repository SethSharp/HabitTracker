<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\StatisticsHelper;

class ShowStatisticsController extends Controller
{
    use StatisticsHelper;

    public function __invoke(): Response
    {
        return Inertia::render('Statistics', [
            'habitsByDay' => $this->getMonthlyHabitScheduleWithHabits(auth()->user()),
            'habits' => Auth::user()->habits()->get()
        ]);
    }
}
