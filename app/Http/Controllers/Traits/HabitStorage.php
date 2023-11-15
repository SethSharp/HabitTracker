<?php

namespace App\Http\Controllers\Traits;

use App\Domain\Frequency\Enums\Frequency;
use App\Domain\Habits\Models\Habit;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use App\Domain\Iam\Models\User;
use Carbon\Carbon;

trait HabitStorage
{
    private function calculatedOccurrenceDays($data, $freq): string
    {
        return match ($freq) {
            Frequency::DAILY->value => json_encode($data['daily_config']),
            Frequency::WEEKLY->value => json_encode([(int)$data['weekly_config']]),
            Frequency::MONTHLY->value => json_encode([$data['monthly_config']]),
            default => now(),
        };
    }

    private function scheduledHabitsOverTimeframe(User $user, Habit $habit, Carbon $scheduledDate, Carbon $endDate): void
    {
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
