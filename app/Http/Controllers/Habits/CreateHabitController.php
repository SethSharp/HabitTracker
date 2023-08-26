<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use App\Enums\Goals;
use Inertia\Inertia;
use App\Enums\Frequency;
use App\Http\Controllers\Controller;
use Inertia\Response;

class CreateHabitController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Habits/Create', [
            'frequencies' => collect(Frequency::cases())->map(function ($data, $index) {
                return ['id' => $index, 'name' => $data->value];
            }),
            'min' => Carbon::now()->toDateString(),
            'max' => Carbon::now()->endOfMonth()->toDateString(),
            'goals' => Goals::cases()
        ]);
    }
}
