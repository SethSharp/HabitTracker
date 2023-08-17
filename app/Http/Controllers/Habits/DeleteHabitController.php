<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Habit;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\HabitStorageTrait;

class DeleteHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(Habit $habit, Request $request): Response
    {
        $habits = $request->user()
            ->scheduledHabits()
            ->where('habit_id', $habit->id)
            ->whereBetween('scheduled_completion', [Carbon::now()->toDateString(), Carbon::now()->endOfMonth()->toDateString()])
            ->get();

        $habit->delete();

        $habits->map(function ($habit) {
            $habit->delete();
        });

        return Inertia::location(url('habits'));
    }
}
