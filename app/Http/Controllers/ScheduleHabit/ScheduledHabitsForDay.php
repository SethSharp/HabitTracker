<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ScheduledHabitsForDay extends Controller
{
    public function __invoke(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(
            $request->user()
            ->scheduledHabits()
            ->where('scheduled_completion', $request->date)
            ->get()
        );
    }
}
