<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Http\Controllers\Traits\HabitStorageTrait;

class UpdateHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(Habit $habit, UpdateHabitRequest $request): Response
    {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $habit->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value),
            'colour' => $data['colour']
        ]);

        ray($data['colour']);

        $habit->save();

        return Inertia::location(url('habits'));
    }
}
