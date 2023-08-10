<?php

namespace App\Http\Controllers\Habits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Models\Habit;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\RestoreHabitRequest;

class RestoreHabitController extends Controller
{
    public function __invoke(RestoreHabitRequest $request): \Symfony\Component\HttpFoundation\Response
    {
        $habit = Habit::withTrashed()->find($request->route('id'));

        $habit->restore();

        $habits = HabitSchedule::withTrashed()->where('habit_id', $habit->id)->get();

        $habits->map(function ($habit) {
            $habit->restore();
        });

        return Inertia::location(url('habits'));
    }
}
