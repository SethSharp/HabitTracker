<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;

class UnComplete extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): \Illuminate\Http\JsonResponse
    {
        $this->authorize('manage', $habitSchedule);

        $habitSchedule->update([
            'completed' => false
        ]);

        $habitSchedule->save();

        ray($habitSchedule);

        return response()->json($habitSchedule['completed']);
    }
}
