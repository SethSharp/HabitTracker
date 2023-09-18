<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Habit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\ScheduledHabits;

class DeleteHabitController extends Controller
{
    use HabitStorage;
    use ScheduledHabits;

    public function __invoke(Habit $habit, Request $request): Response
    {
        $habits = $request->user()
            ->scheduledHabits()
            ->where([
                'habit_id' => $habit->id,
                'completed' => 0
            ])
            ->whereBetween('scheduled_completion', [Carbon::now()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])
            ->get();

        $habit->delete();

        // delete all habit schedules in the future and not complete
        $habits->map(function ($habit) {
            $habit->delete();
        });

        $this->monthlyScheduledHabits($request->user(), month: null, withCaching: true);

        return Inertia::location(url('habits'));
    }
}
