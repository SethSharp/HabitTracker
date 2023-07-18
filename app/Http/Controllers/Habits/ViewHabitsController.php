<?php

namespace App\Http\Controllers\Habits;

use App\Http\Controllers\Controller;
use App\Models\Habit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ViewHabitsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Habits/View', [
            'habits' => Auth::user()->habits()->get()->toArray(),
        ]);
    }
}
