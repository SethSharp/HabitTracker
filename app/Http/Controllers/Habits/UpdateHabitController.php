<?php

namespace App\Http\Controllers\Habits;

use App\Domain\Frequency\Enums\Frequency;
use App\Domain\Goals\Enums\Goals;
use App\Domain\Habits\Models\Habit;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\Http\Controllers\Actions\Habits\UpdateHabitAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Requests\Habits\UpdateHabitRequest;
use Carbon\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class UpdateHabitController extends Controller
{
    use HabitStorage;
    use ScheduledHabits;

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
            'colour' => $data['colour'],
        ]);

        if (is_null($habit->scheduled_to)) {
            $habit->update([
                'scheduled_to' => match ($data['scheduled_to']['length']) {
                    Goals::WEEKLY->value => Carbon::now()->addWeeks($data['scheduled_to']['time'])->toDateString(),
                    Goals::MONTHLY->value => Carbon::now()->addMonths($data['scheduled_to']['time'])->toDateString(),
                    default => null
                }
            ]);
        }

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
            $action($habit, $data, $request->user());
        }

        return Inertia::location(url('habits'));
    }
}
