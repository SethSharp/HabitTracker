<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Models\Habit;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeleteHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(Habit $habit): Response
    {
        $habit->delete();

        $habits = Auth::user()->scheduledHabits()->get();

        $habits->map(function ($habit) {
            $habit->delete();
        });

        return Inertia::location(url('habits'));
    }
}
