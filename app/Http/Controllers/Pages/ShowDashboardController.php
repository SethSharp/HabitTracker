<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Models\HabitSchedule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShowDashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'schedule' => Auth::user()->scheduledHabits()->with('habit')->get()->toArray(),
        ]);
    }
}
