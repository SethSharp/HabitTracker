<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DateHelper;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\ScheduledHabits;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShowDashboardController extends Controller
{
    use ScheduledHabits;
    use HabitLog;
    use DateHelper;

    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'dailyHabits' => $this->getDailyScheduledHabits(Auth::user()),
            'weeklyHabits' => $this->getWeeklyScheduledHabits(Auth::user()),
            'log' => $this->getWeeklyLog(Auth::user(), $this->getDateXDaysAgo(7), $this->getDateXDaysAgo(1))
        ]);
    }
}
