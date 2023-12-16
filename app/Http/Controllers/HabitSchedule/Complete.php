<?php

namespace App\Http\Controllers\HabitSchedule;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class Complete extends Controller
{
    public function __invoke(HabitSchedule $habitSchedule): JsonResponse
    {
        $this->authorize('manage', $habitSchedule);

        $habitSchedule->update([
            'completed' => true
        ]);

        return response()->json($habitSchedule['completed']);
    }
}
