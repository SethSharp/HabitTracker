<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use App\Enums\Goals;
use Inertia\Inertia;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Http\Controllers\Controller;

class EditHabitController extends Controller
{
    public function __invoke(Habit $habit)
    {
        return Inertia::render('Habits/Edit', [
            'habit' => $habit,
            'frequencies' => collect(Frequency::cases())->map(function ($data, $index) {
                return ['id' => $index, 'name' => $data->value];
            }),
            'min' => Carbon::now()->toDateString(),
            'max' => Carbon::now()->endOfMonth()->toDateString(),
            'goals' => Goals::cases()
        ]);
    }
}
