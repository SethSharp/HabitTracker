<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Events\Habits\HabitCompletedEvent;
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
                $scheduledHabit = HabitSchedule::where('id', $id)->with('habit')->get()->first();

                $scheduledHabit->update([
                    'completed' => 1
                ]);

                $habit = $scheduledHabit->habit;

                broadcast(new HabitCompletedEvent($habit));

                // This will not work in some cases, ie where the final scheduled
                // habit does not occur today
                // ie; A saturday plan, but the goal has said that it ends on sunday (even
                // though it isn't scheduled for that day)

                // Check
                $restOfScheduledHabits = $request->user()
                    ->scheduledHabits()
                    ->where('scheduled_completion', '>', Carbon::now()->toDateString())
                    ->get();

                if (count($restOfScheduledHabits) === 0) {
                    $habit->update([
                        'scheduled_to' => null
                    ]);
                }
            }
        }

        return Inertia::location(url('dashboard'));
    }
}
