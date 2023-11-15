<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Http\Controllers\Controller;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class Cancel extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): \Illuminate\Http\JsonResponse
    {
        $this->authorize('manage', $habitSchedule);

        $habitSchedule->update([
            'cancelled' => true,
            'completed' => false,
        ]);

        $habitSchedule->save();

        return response()->json($habitSchedule['cancelled']);
    }
}
