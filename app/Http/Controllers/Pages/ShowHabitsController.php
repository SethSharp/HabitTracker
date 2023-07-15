<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ShowHabitsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Habits');
    }
}
