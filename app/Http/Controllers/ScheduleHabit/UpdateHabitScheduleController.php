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

        // TODO: Make this process more efficient and also, keep track of when this is completed...
        $user = Auth::user()->scheduledHabits()->get()->pluck('id');
        $user->map( function ($id) use ($checkedHabits) {
            if (in_array($id, $checkedHabits)) {
                HabitSchedule::find($id)->update([
                    'completed' => 1
                ]);
            }
        });

        return Inertia::location(url('dashboard'));
    }
}
