<?php

namespace App\App\Http\Controllers\Pages;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\App\Http\Controllers\Controller;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class ShowDashboardController extends Controller
{
    use ScheduledHabits;

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Dashboard', [
            'weeklyHabits' => ! $request->route('week')
                ? $this->getWeeklyScheduledHabits($request->user())
                : $this->getHabitsForRange($request->user(), $request->route('week')),
            'date' => $request->route('week')
        ]);
    }
}
