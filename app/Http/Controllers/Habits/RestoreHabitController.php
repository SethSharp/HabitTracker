<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\DateHelper;

class RestoreHabitController extends Controller
{
    use HabitLog;
    use DateHelper;

    public function __invoke(int $id): \Symfony\Component\HttpFoundation\Response
    {
        $habit = Habit::withTrashed()->find($id);

        $habit->restore();

        $habits = HabitSchedule::withTrashed()->where('habit_id', $habit->id)->get();

        $habits->map(function ($habit) {
            $habit->restore();
        });

        return Inertia::location(url('habits'));
    }
}
