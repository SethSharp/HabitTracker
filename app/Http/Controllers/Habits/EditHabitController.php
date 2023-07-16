<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Http\Controllers\Controller;
use App\Models\Habit;
use Inertia\Inertia;

class EditHabitController extends Controller
{
    public function __invoke(Habit $habit)
    {
        return Inertia::render('Habits/Edit', [
            'habit' => $habit
        ]);
    }
}
