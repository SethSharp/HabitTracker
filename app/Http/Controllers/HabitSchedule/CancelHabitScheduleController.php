<?php

namespace App\Http\Controllers\HabitSchedule;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\Http\Requests\HabitSchedule\CancelHabitScheduleRequest;

class CancelHabitScheduleController extends Controller
{
    public function __invoke(CancelHabitScheduleRequest $request, HabitSchedule $habitSchedule): RedirectResponse
    {
        $habitSchedule->update([
            'completed' => false,
            'cancelled' => true
        ]);

        return redirect()
            ->back()
            ->with('success', 'Habit has been cancelled!');
    }
}
