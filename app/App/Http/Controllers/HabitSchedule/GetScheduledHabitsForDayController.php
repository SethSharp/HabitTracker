<?php

namespace App\App\Http\Controllers\HabitSchedule;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\App\Http\Controllers\Controller;
use App\App\Http\Controllers\Traits\ScheduledHabits;

class GetScheduledHabitsForDayController extends Controller
{
    use ScheduledHabits;

    public function __invoke(Request $request): JsonResponse
    {
        return response()->json($this->getDailyScheduledHabits($request->user(), $request->date));
    }
}
