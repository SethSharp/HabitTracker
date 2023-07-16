<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Models\Habit;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    public function __invoke(StoreHabitRequest $request): Response
    {
        $data = collect($request->validated());

        Habit::factory()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => Frequency::cases()[$data['frequency']]->value,
            'occurrence_days' => json_encode($data['daily_config'])
        ]);

        return Inertia::location(url('habits'));
    }
}
