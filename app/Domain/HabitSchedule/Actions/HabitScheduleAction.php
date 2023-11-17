<?php

namespace App\Domain\HabitSchedule\Actions;

use Carbon\Carbon;
use App\Domain\Iam\Models\User;
use Illuminate\Support\Collection;
use App\Domain\Habits\Models\Habit;
use App\Domain\Frequency\Enums\Frequency;
use App\Http\Controllers\Traits\HabitStorage;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class HabitScheduleAction
{
    use HabitStorage;

    public function __invoke(
        User        $user,
        Frequency   $frequency,
        Habit       $habit,
        string|null $scheduledTo,
        Collection  $data
    ): void {
        if ($frequency->value == Frequency::MONTHLY->value) {
            HabitSchedule::factory()->create([
                'user_id' => $user->id,
                'habit_id' => $habit->id,
                'scheduled_completion' => $data['monthly_config'],
            ]);
        }

        $scheduledDate = Carbon::now()->startOfWeek();

        $isSet = isset($scheduledTo) && ! is_null($data['scheduled_to']);

        $endDate = $isSet
            ? Carbon::parse($scheduledTo) > Carbon::now()->endOfMonth()
                ? Carbon::now()->endOfMonth()
                : Carbon::parse($scheduledTo)
            : Carbon::now()->endOfMonth();

        if (isset($data['start_next_week']) && ! is_null($data['start_next_week']) && $data['start_next_week']) {
            $scheduledDate->addWeek();
        }

        $occurrences = json_decode($habit->occurrence_days);

        while ($scheduledDate <= $endDate) {
            // if today is a day in occurrences add to list
            if (in_array($scheduledDate->dayOfWeek, $occurrences)) {
                if ($scheduledDate >= Carbon::now()->toDateString()) {
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => $user->id,
                        'scheduled_completion' => $scheduledDate
                    ]);
                }
            }
            $scheduledDate->addDay();
        }
    }
}
