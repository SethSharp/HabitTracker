<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Domain\Goals\Enums\Goals;
use App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Habits\Actions\StoreHabitAction;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Domain\Habits\DataTransferObjects\StoreHabitData;
use App\Domain\HabitSchedule\Actions\StoreHabitScheduleAction;

class StoreHabitController extends Controller
{
    public function __invoke(
        StoreHabitRequest        $request,
        StoreHabitAction         $storeHabitAction,
        StoreHabitScheduleAction $habitScheduleAction,
    ): Response {
        $data = collect($request->validated());

        $freq = Frequency::cases()[$data['frequency']];

        $scheduledToDate = match ($data['scheduled_to']['length']) {
            Goals::WEEKLY->value => Carbon::now()->addWeeks($data['scheduled_to']['time'])->toDateString(),
            Goals::MONTHLY->value => Carbon::now()->addMonths($data['scheduled_to']['time'])->toDateString(),
            default => null
        };

        $habit = $storeHabitAction($request->user(), StoreHabitData::fromRequest(
            $request,
            $freq->value,
            $scheduledToDate,
            match ($freq->value) {
                Frequency::DAILY->value => json_encode($data['daily_config']),
                Frequency::WEEKLY->value => json_encode([(int)$data['weekly_config']]),
                Frequency::MONTHLY->value => json_encode([$data['monthly_config']]),
                default => now(),
            }
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
