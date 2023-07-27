<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use App\Http\Controllers\Controller;

class ShowCompetitionController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Competition');
    }
}
