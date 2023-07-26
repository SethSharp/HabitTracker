<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Http\Controllers\Traits\HabitStorageTrait;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    use HabitStorageTrait;
    use ScheduledHabits;

    public function __invoke(StoreHabitRequest $request): Response
    {
        $data = collect($request->validated());

        ray($data);

        $freq = Frequency::cases()[$data['frequency']];

        $habit = Habit::factory()->create([
            'user_id' => Auth::user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value)
        ]);

        // Next week will be calculated in command
        if (! $data['start_next_week']) {
            $occurrences = json_decode($habit->occurrence_days);

            foreach ($occurrences as $occurrence) {
                HabitSchedule::factory()->create([
                    'habit_id' => $habit->id,
                    'user_id' => Auth::user()->id,
                    'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence)
                ]);
            }
        }

        return Inertia::location(url('habits'));
    }
}
