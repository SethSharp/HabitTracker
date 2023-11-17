<?php

namespace App\Domain\HabitSchedule\Actions;

use Carbon\Carbon;
use App\Domain\Iam\Models\User;
use Illuminate\Support\Collection;
use App\Domain\Habits\Models\Habit;
use App\Domain\Frequency\Enums\Frequency;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class UpdateHabitScheduleAction
{
    public function __invoke(
        User       $user,
        Frequency $frequency,
        Habit      $habit,
        Collection $data,
    ): void {
        if ($frequency->value == Frequency::MONTHLY->value) {
            $oldDate = $habit->occurrence_days;
            $date = json_decode($oldDate)[0];
            $scheduledHabits = $user
                ->scheduledHabits()
                ->where([
                    'habit_id' => $habit->id,
                    'scheduled_completion' => $date
                ])
                ->get();

            $scheduledHabits->each(fn ($habit) => $habit->delete());

            HabitSchedule::factory()->create([
                'habit_id' => $habit->id,
                'user_id' => $user,
                'scheduled_completion' => $data['monthly_config'],
            ]);
        }

        $occurrences = json_decode($habit->occurrence_days);
        $scheduledDate = Carbon::now();

        while ($scheduledDate <= Carbon::now()->endOfMonth()) {
            $scheduledHabitsForToday = $user->scheduledHabits()
                ->where([
                    'habit_id' => $habit->id,
                    'scheduled_completion' => $scheduledDate->toDateString(),
                ])
                ->get();

            // if today is a day in occurrences add to list
            if (in_array($scheduledDate->dayOfWeek, $occurrences)) {
                if (count($scheduledHabitsForToday) === 0) {
                    HabitSchedule::factory()->create([
                        'habit_id' => $habit->id,
                        'user_id' => $user->id,
                        'scheduled_completion' => $scheduledDate
                    ]);
                }
            } else {
                $scheduledHabitsForToday->each(function ($habit) {
                    $habit->delete();
                });
            }

            $scheduledDate->addDay();
        }
    }
}
