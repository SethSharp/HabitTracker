<?php

namespace App\Http\Controllers\ScheduleHabit;

use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Http\Requests\UpdateHabitScheduleRequest;
use App\Models\HabitSchedule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UpdateHabitScheduleController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(UpdateHabitScheduleRequest $request): Response
    {
        $checkedHabits = $request->validated()['habits'];
        
        Auth::user()->scheduledHabits()->get()->pluck('id')
            ->map( function ($id) use ($checkedHabits) {
                $habit = HabitSchedule::find($id);
                if (in_array($id, $checkedHabits)) {
                    if ($habit->completed === 0) {
                        $habit->update([
                            'completed' => 1
                        ]);
                    }
                } else {
                    if ($habit->completed === 1) {
                        $habit->update([
                            'completed' => 0
                        ]);
                    }
                }
            }
        );

        return Inertia::location(url('dashboard'));
    }
}
