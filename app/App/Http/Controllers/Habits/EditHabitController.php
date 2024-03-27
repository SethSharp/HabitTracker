<?php

namespace App\App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Domain\Habits\Models\Habit;
use App\App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;

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
        ]);
    }
}
