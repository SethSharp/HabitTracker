<?php

namespace App\Http\Controllers\ScheduleHabit;

use Inertia\Inertia;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\UpdateHabitScheduleRequest;

class UpdateHabitScheduleController extends Controller
{
    use HabitStorage;

    public function __invoke(UpdateHabitScheduleRequest $request): Response
    {
        $checkedHabits = $request->validated()['habits'];

        $user = Auth::user()->scheduledHabits()->where('completed', 0)->get()->pluck('id');
        $user->map(function ($id) use ($checkedHabits) {
            if (in_array($id, $checkedHabits)) {
                HabitSchedule::find($id)->update([
                    'completed' => 1
                ]);
            }
        });

        return Inertia::location(url('dashboard'));
    }
}
