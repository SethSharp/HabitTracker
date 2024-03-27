<?php

namespace App\App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Domain\Habits\Models\Habit;
use App\App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class DeleteHabitController extends Controller
{
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

        return Inertia::location(url('habits'));
    }
}
