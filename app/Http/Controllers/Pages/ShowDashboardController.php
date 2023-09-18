<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScheduledHabits;

class ShowDashboardController extends Controller
{
    use ScheduledHabits;

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'dailyHabits' => $this->getDailyScheduledHabits($request->user()),
            'completedHabits' => $this->getCompletedDailyHabits($request->user()),
            'weeklyHabits' => $this->getWeeklyScheduledHabits($request->user()),
            'statistics' => [
                'streak' => $request->user()->streak,
                'bestStreak' => $request->user()->best_streak,
            ]
        ]);
    }
}
