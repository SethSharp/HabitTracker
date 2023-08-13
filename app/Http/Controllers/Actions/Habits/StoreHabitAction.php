<?php

namespace App\Http\Controllers\Actions\Habits;

use App\Enums\Frequency;
use Carbon\Carbon;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\DateHelper;
use App\Http\Controllers\Traits\ScheduledHabits;

class StoreHabitAction
{
    use ScheduledHabits;
    use DateHelper;

    public function __invoke(Habit $habit, Collection $data, Frequency $freq)
    {
        $occurrences = json_decode($habit->occurrence_days);

        $monday = $this->getMonday();

        foreach ($occurrences as $occurrence) {
            $startOfTheWeek = Carbon::parse($monday);

            if (! is_string($occurrence)) {
                if ($startOfTheWeek->addDays($occurrence-1) < date('Y-m-d')) {
                    continue;
                }

                $startOfTheWeek->subDays($occurrence-1);
            }

            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => Auth::user()->id,
                'scheduled_completion' => $this->determineDateForHabitCompletion($freq, $occurrence, $startOfTheWeek)
            ]);
        }
    }
}
