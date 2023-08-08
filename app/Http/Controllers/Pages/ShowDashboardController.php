<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\DateHelper;
use App\Http\Controllers\Traits\ScheduledHabits;

class ShowDashboardController extends Controller
{
    use ScheduledHabits;
    use DateHelper;

    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'dailyHabits' => $this->getDailyScheduledHabits(Auth::user()),
            'completedHabits' => $this->getCompletedDailyHabits(Auth::user()),
            'weeklyHabits' => $this->getWeeklyScheduledHabits(Auth::user()),
        ]);
    }
}
