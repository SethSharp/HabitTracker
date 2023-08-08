<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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
