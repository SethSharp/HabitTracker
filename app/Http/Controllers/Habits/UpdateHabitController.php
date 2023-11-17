<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Domain\Goals\Enums\Goals;
use App\Domain\Habits\Models\Habit;
use App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Habits\Actions\UpdateHabitAction;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Requests\Habits\UpdateHabitRequest;
use App\Domain\Habits\DataTransferObjects\UpdateHabitData;
use App\Domain\HabitSchedule\Actions\UpdateHabitScheduleAction;

class UpdateHabitController extends Controller
{
    use ScheduledHabits;

    public function __invoke(
        Habit                     $habit,
        UpdateHabitRequest        $request,
        UpdateHabitAction         $updateHabitAction,
        UpdateHabitScheduleAction $updateHabitScheduleAction,
    ): Response {
        $data = collect($request->validated());
        $freq = Frequency::cases()[$data['frequency']];

        $updateHabitAction(
            $request->user(),
            $habit,
            UpdateHabitData::fromRequest(
                $request,
                $freq->value,
                match ($data['scheduled_to']['length']) {
                    Goals::WEEKLY->value => Carbon::now()->addWeeks($data['scheduled_to']['time'])->toDateString(),
                    Goals::MONTHLY->value => Carbon::now()->addMonths($data['scheduled_to']['time'])->toDateString(),
                    default => null
                }
            )
        );

        $updateHabitScheduleAction(
            $request->user(),
            $freq,
            $habit,
            $data
        );

        return Inertia::location(url('habits'));
    }
}
