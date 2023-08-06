<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\DateHelper;

class ViewHabitsController extends Controller
{
    use HabitLog;
    use DateHelper;

    public function __invoke()
    {
        return Inertia::render('Habits/View', [
            'habits' => Auth::user()->habits()->withTrashed()->get()->toArray(),
            'log' => $this->getHabitLog(Auth::user(), $this->getDateXDaysAgo(14), $this->getDateXDaysAgo(1)),
        ]);
    }
}
