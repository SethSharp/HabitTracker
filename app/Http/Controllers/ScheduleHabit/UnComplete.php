<?php

namespace App\Http\Controllers\ScheduleHabit;

use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;

class UnComplete extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): \Illuminate\Http\JsonResponse
    {
        $habitSchedule->update([
            'completed' => false
        ]);

        $habitSchedule->save();

        ray($habitSchedule);

        return response()->json($habitSchedule['completed']);
    }
}
