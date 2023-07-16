<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use Inertia\Inertia;

class ViewHabitsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Habits/View', [
            'habits' => Habit::all(),
        ]);
    }
}
