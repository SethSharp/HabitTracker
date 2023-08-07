<?php

namespace App\Http\Controllers\Habits;

use App\Models\HabitSchedule;
use Inertia\Inertia;
use App\Models\Habit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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

        ray($habits);

        $habits->map(function ($habit) {
            $habit->restore();
        });

        return Inertia::location(url('habits'));
    }
}
