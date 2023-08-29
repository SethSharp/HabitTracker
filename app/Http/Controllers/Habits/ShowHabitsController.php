<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitLogHelper;

class ShowHabitsController extends Controller
{
    use HabitLogHelper;

    public function __invoke(Request $request): Response
    {
        return Inertia::render('Habits/Show', [
            'habits' => $request->user()
                ->habits()
                ->get()
                ->map(function ($habit) {
                    $habit['days_left'] = Carbon::parse($habit['scheduled_to'])->diffInDays(Carbon::today());
                    return $habit;
                })
                ->toArray(),
            'log' => $this->getHabitLog($request->user(), Carbon::now()->subDays(14)->toDateString(), Carbon::now()->subDay()->toDateString()),
        ]);
    }
}
