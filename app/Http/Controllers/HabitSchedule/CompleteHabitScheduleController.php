<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\Http\Requests\HabitSchedule\CompleteHabitScheduleRequest;

class CompleteHabitScheduleController extends Controller
{
    public function __invoke(CompleteHabitScheduleRequest $request, HabitSchedule $habitSchedule): RedirectResponse
    {
        $habitSchedule->update([
            'completed' => true
        ]);

        return redirect()
            ->back()
            ->with('success', 'Habit has been completed!');
    }
}
