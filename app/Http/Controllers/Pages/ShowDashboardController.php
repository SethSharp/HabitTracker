<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ShowDashboardController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            // will become a scheduled habit query
            'habits' => Auth::user()->habits()->get()->toArray(),
        ]);
    }
}
