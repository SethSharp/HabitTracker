<?php

namespace App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Http\Controllers\Actions\Habits\UpdateHabitAction;

class UpdateHabitController extends Controller
{
    use HabitStorage;

    public function __invoke(Habit $habit, UpdateHabitRequest $request, UpdateHabitAction $action): Response
    {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $oldDate = $habit->occurrence_days;

        // Sets the config for the habit
        $habit->update([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value),
            'scheduled_to' => $data['scheduled_to'],
            'colour' => $data['colour']
        ]);

        if (! $data['start_next_week']) {
            if ($freq->value == Frequency::MONTHLY->value) {
                $date = json_decode($oldDate)[0];
                $scheduledHabits = $request->user()
                    ->scheduledHabits()
                    ->where([
                        'habit_id' => $habit->id,
                        'scheduled_completion' => $date
                    ])
                    ->get();

                $scheduledHabits->each(fn ($habit) => $habit->delete());

                HabitSchedule::factory()->create([
                    'habit_id' => $habit->id,
                    'user_id' => $request->user(),
                    'scheduled_completion' => $data['monthly_config'],
                ]);
            } else {
                // Update habit schedules starting from today
                $action($habit, $data, $request->user());
            }
        }


        return Inertia::location(url('habits'));
    }
}
