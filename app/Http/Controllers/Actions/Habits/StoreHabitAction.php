<?php

namespace App\Http\Controllers\Actions\Habits;

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

    public function __invoke(Habit $habit, Collection $data): void
    {
        $occurrences = json_decode($habit->occurrence_days);
        $scheduledDate = Carbon::parse($this->getMonday());
        $endDate = $data['scheduled_to'] ? Carbon::parse($data['scheduled_to']) : Carbon::now()->endOfMonth();

        if ($data['start_next_week']) {
            $scheduledDate->addWeek();
        }

        while ($scheduledDate < $endDate) {
            // if today is a day in occurrences add to list
            if (in_array($scheduledDate->dayOfWeek, $occurrences)) {
                // if not in the past add to the schedule
                if (! $scheduledDate <= Carbon::now()) {
                    // create schedule
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => Auth::user()->id,
                        'scheduled_completion' => $scheduledDate
                    ]);
                }
            }
            $scheduledDate->addDay();
        }
    }
}
