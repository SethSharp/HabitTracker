<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Http\Controllers\Controller;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class Complete extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): \Illuminate\Http\JsonResponse
    {
        $this->authorize('manage', $habitSchedule);

        $habitSchedule->update([
            'completed' => true
        ]);

        $habitSchedule->save();

        return response()->json($habitSchedule['completed']);
    }
}
