<?php

namespace App\Domain\Habits\Actions;

use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;
use App\Domain\Habits\DataTransferObjects\UpdateHabitData;

class UpdateHabitAction
{
    public function __invoke(
        User $user,
        Habit $habit,
        UpdateHabitData $updateHabitData
    ): void {
        $habit->update([
            'name' => $updateHabitData->name,
            'description' => $updateHabitData->description,
            'frequency' => $updateHabitData->frequency,
            'colour' => $updateHabitData->colour,
            'scheduled_to' => ! is_null($habit->scheduled_to)
                ? $habit->scheduled_to
                : $updateHabitData->scheduledTo
        ]);
    }
}
