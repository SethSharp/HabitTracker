<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;
use Inertia\Inertia;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\ScheduledHabits;

class UpdateHabitScheduleController extends Controller
{
    use HabitStorage;
    use ScheduledHabits;

    public function __invoke(Request $request): Response
    {
        $updatedHabits = $request->input('habits');

        $scheduledHabitsForToday = $request->user()
            ->scheduledHabits()
            ->where('scheduled_completion', Carbon::now()->toDateString())
            ->get();

        foreach ($scheduledHabitsForToday as $scheduledHabitForToday) {
            $completed = 0;
            if (in_array($scheduledHabitForToday->id, $updatedHabits)) {
                $completed = 1;
            }

            $scheduledHabitForToday->update([
                'completed' => $completed
            ]);

            // habit goals
            if (! is_null($scheduledHabitForToday->habit->scheduled_to) &&
                Carbon::now() == Carbon::parse($scheduledHabitForToday->habit->scheduled_to)) {

                if ($completed === 0) {
                    // un-ticked or not completed just yet
                } else {
                    // ticked off
                }
            }
        }

        $this->monthlyScheduledHabits($request->user(), month: null, withCaching: true);

        return Inertia::location(url('dashboard'));
    }
}
