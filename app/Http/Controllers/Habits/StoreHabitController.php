<?php

namespace App\Http\Controllers\Habits;

use App\Domain\Frequency\Enums\Frequency;
use App\Domain\Goals\Enums\Goals;
use App\Domain\Habits\Models\Habit;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\Http\Controllers\Actions\Habits\StoreHabitAction;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\HabitStorage;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Requests\Habits\StoreHabitRequest;
use Carbon\Carbon;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    use HabitStorage;
    use ScheduledHabits;

    public function __invoke(StoreHabitRequest $request, StoreHabitAction $action): Response
    {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $scheduledToDate = match ($data['scheduled_to']['length']) {
            Goals::WEEKLY->value => Carbon::now()->addWeeks($data['scheduled_to']['time'])->toDateString(),
            Goals::MONTHLY->value => Carbon::now()->addMonths($data['scheduled_to']['time'])->toDateString(),
            default => null
        };

        $habit = Habit::factory()->create([
            'user_id' => $request->user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'scheduled_to' => $scheduledToDate,
            'occurrence_days' => $this->calculatedOccurrenceDays($data, $freq->value),
            'colour' => $data['colour']
        ]);

        if ($freq->value == Frequency::MONTHLY->value) {
            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $request->user()->id,
                'scheduled_completion' => $data['monthly_config'],
            ]);
        } else {
            $action($request->user(), $habit, $scheduledToDate, $data);
        }

        return Inertia::location(url('habits'));
    }
}
