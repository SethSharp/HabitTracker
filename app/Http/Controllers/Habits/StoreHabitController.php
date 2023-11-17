<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Domain\Goals\Enums\Goals;
use App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;
use App\Http\Controllers\Traits\HabitStorage;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Habits\Actions\StoreHabitAction;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Domain\Habits\DataTransferObjects\StoreHabitData;
use App\Domain\HabitSchedule\Actions\HabitScheduleAction;

class StoreHabitController extends Controller
{
    use HabitStorage;
    use ScheduledHabits;

    public function __invoke(
        StoreHabitRequest   $request,
        StoreHabitAction    $storeHabitAction,
        HabitScheduleAction $habitScheduleAction,
    ): Response {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $scheduledToDate = match ($data['scheduled_to']['length']) {
            Goals::WEEKLY->value => Carbon::now()->addWeeks($data['scheduled_to']['time'])->toDateString(),
            Goals::MONTHLY->value => Carbon::now()->addMonths($data['scheduled_to']['time'])->toDateString(),
            default => null
        };

        $occurrenceDays = $this->calculatedOccurrenceDays($data, $freq->value);

        $habit = $storeHabitAction($request->user(), StoreHabitData::fromRequest(
            $request,
            $freq->value,
            $scheduledToDate,
            $occurrenceDays
        ));

        $habitScheduleAction(
            $request->user(),
            $freq,
            $habit,
            $scheduledToDate,
            $data
        );

        return Inertia::location(url('habits'));
    }
}
