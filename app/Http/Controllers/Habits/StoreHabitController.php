<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use Illuminate\Foundation\Testing\Concerns\InteractsWithContainer;
use Inertia\Inertia;

class StoreHabitController extends Controller
{
    public function __invoke(StoreHabitRequest $request)
    {
        ray($request->validated());
        return Inertia::location(url('habits'));
    }
}
