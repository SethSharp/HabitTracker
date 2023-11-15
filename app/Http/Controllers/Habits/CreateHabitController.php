<?php

namespace App\Http\Controllers\Habits;

use App\Domain\Frequency\Enums\Frequency;
use App\Domain\Goals\Enums\Goals;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Inertia\Inertia;
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
