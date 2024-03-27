<?php

namespace App\App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use Inertia\Response;
use App\App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;

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
        ]);
    }
}
