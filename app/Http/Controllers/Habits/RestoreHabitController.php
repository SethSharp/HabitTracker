<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Models\Habit;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class RestoreHabitController extends Controller
{
    public function __invoke(Habit $habit): Response
    {
        ray($habit);

        return Inertia::location(url('habits'));
    }
}
