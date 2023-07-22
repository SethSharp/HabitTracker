<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScheduledHabits;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShowDashboardController extends Controller
{
    use ScheduledHabits;

    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'schedule' => $this->getScheduledHabitsForUser(Auth::user()),
        ]);
    }
}
