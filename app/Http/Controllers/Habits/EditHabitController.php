<?php

namespace App\Http\Controllers\Habits;

use App\Domain\Frequency\Enums\Frequency;
use App\Domain\Goals\Enums\Goals;
use App\Domain\Habits\Models\Habit;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\Inertia;

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
