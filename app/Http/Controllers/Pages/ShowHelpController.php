<?php

namespace App\Http\Controllers\Pages;

use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

class ShowHelpController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Help');
    }
}
