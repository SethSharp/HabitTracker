<?php

namespace App\App\Http\Controllers\Habits;

use Inertia\Inertia;
use App\App\Http\Controllers\Controller;
use App\Domain\Frequency\Enums\Frequency;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Habits\Actions\StoreHabitAction;
use App\App\Http\Requests\Habits\StoreHabitRequest;
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

        $habit = $storeHabitAction($request->user(), StoreHabitData::fromRequest(
            $request,
            $freq->value,
            isset($data['scheduled_to']) ? $data['scheduled_to'] : null,
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
            isset($data['scheduled_to']) ? $data['scheduled_to'] : null,
            $data
        );

        return Inertia::location(url('habits'));
    }
}
