<?php

namespace App\Http\Controllers\Habits;

use App\Enums\Frequency;
use App\Models\Habit;
use Carbon\Carbon;
use DateTime;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Http\Requests\Habits\StoreHabitRequest;
use Symfony\Component\HttpFoundation\Response;

class StoreHabitController extends Controller
{
    public function __invoke(StoreHabitRequest $request): Response
    {
        $data = collect($request->validated());

        // TODO: Need to get the day if month is chosen then save the day: [16] not [23-6-16]

        $freq = Frequency::cases()[$data['frequency']];

        Habit::factory()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'frequency' => $freq,
            'occurrence_days' => $this->matchConfig($data, $freq->value)
        ]);

        return Inertia::location(url('habits'));
    }

    private function matchConfig($data, $freq): string
    {
        switch ($freq) {
            case Frequency::DAILY->value:
                return json_encode($data['daily_config']);
            case Frequency::WEEKLY->value:
                return json_encode($data['weekly_config']);
            case Frequency::MONTHLY->value:
                $date = DateTime::createFromFormat('Y-n-j', $data['monthly_config']);
                return json_encode([$date->format('j')]);
            default:
                return now();
        }
    }
}
