<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\DateHelper;
use App\Http\Controllers\Traits\HabitLog;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ViewHabitsController extends Controller
{
    use HabitLog;
    use DateHelper;

    public function __invoke()
    {
        return Inertia::render('Habits/View', [
            'habits' => Auth::user()->habits()->get()->toArray(),
            'log' => $this->getHabitLog(Auth::user(), $this->getDateXDaysAgo(14), $this->getDateXDaysAgo(1)),
        ]);
    }
}
