<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Models\Habit;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Traits\HabitStorageTrait;

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