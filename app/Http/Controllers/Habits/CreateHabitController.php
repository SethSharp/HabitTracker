<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Enums\Frequency;
use App\Http\Controllers\Controller;

class CreateHabitController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Habits/Create', [
            'frequencies' => collect(Frequency::cases())->map(function ($data, $index) {
                return ['id' => $index, 'name' => $data->value];
            }),
            'min' => date('Y-m-d'),
            'max' => date('Y-m-t')
        ]);
    }
}
