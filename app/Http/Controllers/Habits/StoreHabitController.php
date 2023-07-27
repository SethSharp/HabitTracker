<?php

namespace App\Http\Controllers\Habits;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\Habit;
use App\Enums\Frequency;
use App\Models\HabitSchedule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\DateHelper;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Habits\StoreHabitRequest;
use App\Http\Controllers\Traits\ScheduledHabits;
use App\Http\Controllers\Traits\HabitStorageTrait;

class StoreHabitController extends Controller
{
    use HabitStorageTrait;
    use ScheduledHabits;
    use DateHelper;

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

            $monday = $this->getMonday();

            foreach ($occurrences as $occurrence) {
                $startOfTheWeek = Carbon::parse($monday);
                if ($startOfTheWeek->addDays($occurrence-1) >= Carbon::parse(date('Y-m-d'))) continue;
                HabitSchedule::factory()->create([
                    'habit_id' => $habit->id,
                    'user_id' => Auth::user()->id,
                    'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence, $startOfTheWeek)
                ]);
            }
        }

        return Inertia::location(url('habits'));
    }
}
