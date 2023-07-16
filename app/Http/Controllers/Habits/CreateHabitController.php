<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use Inertia\Inertia;

class CreateHabitController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Habits/create');
    }
}
