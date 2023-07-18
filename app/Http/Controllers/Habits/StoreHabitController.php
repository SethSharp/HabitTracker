<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Http\Requests\Habits\HabitRequest;
use App\Models\Habit;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    use HabitStorageTrait;

    public function __invoke(HabitRequest $request): Response
    {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        Habit::factory()->create([
            'user_id' => Auth::user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value)
        ]);

        return Inertia::location(url('habits'));
    }
}
