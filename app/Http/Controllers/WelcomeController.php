<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Route;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\HabitLog;
use App\Http\Controllers\Traits\DateHelper;

class WelcomeController extends Controller
{
    public function __invoke()
    {
        if (! Auth::check()) {
            return Inertia::render('Welcome', [
                'canLogin' => \Illuminate\Support\Facades\Auth::check(),
                'canRegister' => true,
            ]);
        }

        return Inertia::location(url('dashboard'));
    }
}
