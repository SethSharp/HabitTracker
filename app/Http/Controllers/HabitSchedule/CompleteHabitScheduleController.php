<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Http\Requests\HabitSchedule\CompleteHabitScheduleRequest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class CompleteHabitScheduleController extends Controller
{
    public function __invoke(CompleteHabitScheduleRequest $request, HabitSchedule $habitSchedule): JsonResponse
    {
        ray('attemping');
//        $habitSchedule->update([
//            'completed' => true
//        ]);

        return response()->json($habitSchedule['completed']);
    }
}
