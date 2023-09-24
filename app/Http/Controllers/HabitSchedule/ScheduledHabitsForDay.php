<?php

namespace App\Http\Controllers\HabitSchedule;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduledHabitsForDay extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $request->user()
            ->scheduledHabits()
            ->with('habit')
            ->where('scheduled_completion', $request->date)
            ->get()
        );
    }
}
