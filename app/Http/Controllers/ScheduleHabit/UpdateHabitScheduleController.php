<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateHabitScheduleRequest;

class UpdateHabitScheduleController extends Controller
{
    use HabitStorage;

    public function __invoke(UpdateHabitScheduleRequest $request): Response
    {
        $checkedHabits = $request->validated()['habits'];

        $todayHabitsIds = $request->user()
            ->scheduledHabits()
            ->where('completed', 0)
            ->where('scheduled_completion', Carbon::now()->toDateString())
            ->get()
            ->pluck('id');

        foreach ($todayHabitsIds as $id) {
            if (in_array($id, $checkedHabits)) {
                $scheduledHabit = HabitSchedule::find($id)->update([
                    'completed' => 1
                ]);

                $habit = $scheduledHabit->habit();

                if ($habit->scheduled_to && $habit->scheduled_to === Carbon::now()->toDateString()) {
                    ray('goal completed');
                }
            }
        }

        return Inertia::location(url('dashboard'));
    }
}
