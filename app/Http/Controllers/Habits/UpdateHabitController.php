<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Http\Requests\Habits\HabitRequest;
use App\Models\Habit;
use Carbon\Carbon;
use DateTime;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use Symfony\Component\HttpFoundation\Response;

class UpdateHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(Habit $habit, HabitRequest $request): Response
    {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $habit->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value)
        ]);

        $habit->save();

        return Inertia::location(url('habits'));
    }
}
