<?php

namespace App\Http\Controllers\ScheduleHabit;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateHabitScheduleRequest;

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
            if (in_array($todayHabit->id, $updatedHabits)) {
                $todayHabit->update([
                    'completed' => 1
                ]);
            } else {
                $todayHabit->update([
                    'completed' => 0
                ]);
            }
        }

        return Inertia::location(url('dashboard'));
    }
}
