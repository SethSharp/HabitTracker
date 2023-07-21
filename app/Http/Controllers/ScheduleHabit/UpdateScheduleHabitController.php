<?php

namespace App\Http\Controllers\ScheduleHabit;

use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Http\Requests\UpdateSchedulelHabitRequest;
use App\Models\HabitSchedule;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class UpdateScheduleHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(UpdateSchedulelHabitRequest $request): Response
    {
        collect($request->validated()['habits'])->map( function ($habit_id) {
            HabitSchedule::find($habit_id)->update([
                'completed' => 1
            ]);
        });

        return Inertia::location(url('dashboard'));
    }
}
