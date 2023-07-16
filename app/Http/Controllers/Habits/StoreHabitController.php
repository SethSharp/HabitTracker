<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    public function __invoke(StoreHabitRequest $request): Response
    {
        ray($request->validated());
        return Inertia::location(url('habits'));
    }
}
