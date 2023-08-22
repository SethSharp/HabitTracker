<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\DateHelper;

class ViewHabitsController extends Controller
{
    use HabitLog;
    use DateHelper;

    public function __invoke(): \Inertia\Response
    {
        return Inertia::render('Habits/View', [
            'habits' => Auth::user()->habits()->get()->toArray(),
            'log' => $this->getHabitLog(Auth::user(), Carbon::now()->subDays(14)->toDateString(), Carbon::now()->subDay()->toDateString()),
        ]);
    }
}
