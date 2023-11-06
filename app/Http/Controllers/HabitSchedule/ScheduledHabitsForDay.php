<?php

namespace App\Http\Controllers\HabitSchedule;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ScheduledHabits;

class ScheduledHabitsForDay extends Controller
{
    use ScheduledHabits;

    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($this->getDailyScheduledHabits($request->user(), $request->date));
    }
}
