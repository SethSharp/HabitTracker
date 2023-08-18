<?php

namespace App\Http\Controllers\Actions\Habits;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Habit;
use App\Models\HabitSchedule;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Traits\DateHelper;
use App\Http\Controllers\Traits\ScheduledHabits;

class UpdateHabitAction
{
    use ScheduledHabits;
    use DateHelper;

    public function __invoke(Habit $habit, Collection $data, User $user): void
    {
        $occurrences = json_decode($habit->occurrence_days);
        $scheduledDate = Carbon::now();
        $endDate = Carbon::now()->endOfMonth();

        while ($scheduledDate <= $endDate) {

            $alreadyScheduled = $user->scheduledHabits()
                ->where([
                    'habit_id' => $habit->id,
                    'scheduled_completion' => $scheduledDate->toDateString(),
                ])
                ->get();

            // if today is a day in occurrences add to list
            if (in_array($scheduledDate->dayOfWeek, $occurrences)) {
                if (count($alreadyScheduled) === 0) {
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => Auth::user()->id,
                        'scheduled_completion' => $scheduledDate
                    ]);
                }
            } else {
                $alreadyScheduled->each(fn ($habit) => $habit->delete());
            }

            $scheduledDate->addDay();
        }
    }
}
