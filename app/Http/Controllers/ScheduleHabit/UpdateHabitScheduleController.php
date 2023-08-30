<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;

class UpdateHabitScheduleController extends Controller
{
    use HabitStorage;

    public function __invoke(Request $request): Response
    {
        $updatedHabits = $request->input('habits');

        $todayHabits = $request->user()
            ->scheduledHabits()
            ->where('scheduled_completion', Carbon::now()->toDateString())
            ->get();

        foreach ($todayHabits as $todayHabit) {
            $completed = 0;
            if (in_array($todayHabit->id, $updatedHabits)) {
                $completed = 1;
            }

            // TODO: How will we handle goals / habits with a scheduled to date
            //      now that we allow habits to be updated (From completed to not completed)

            $todayHabit->update([
                'completed' => $completed
            ]);
        }

        return Inertia::location(url('dashboard'));
    }
}
