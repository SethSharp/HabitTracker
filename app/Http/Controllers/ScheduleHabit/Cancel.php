<?php

namespace App\Http\Controllers\ScheduleHabit;

use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;

class Cancel extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): \Illuminate\Http\JsonResponse
    {
        $habitSchedule->update([
            'cancelled' => true,
            'completed' => false,
        ]);

        $habitSchedule->save();

        return response()->json($habitSchedule['cancelled']);
    }
}
