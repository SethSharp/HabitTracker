<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Inertia\Response;

class ShowHelpController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Help');
    }
}
