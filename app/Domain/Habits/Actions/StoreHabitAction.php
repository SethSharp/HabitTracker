<?php

namespace App\Domain\Habits\Actions;

use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;
use App\Domain\Habits\DataTransferObjects\StoreHabitData;

class StoreHabitAction
{
    public function __invoke(User $user, StoreHabitData $storeHabitData): Habit
    {
        return Habit::factory()->create([
            'user_id' => $user->id,
            'name' => $storeHabitData->name,
            'description' => $storeHabitData->description,
            'frequency' => $storeHabitData->frequency,
            'scheduled_to' => $storeHabitData->scheduledTo,
            'occurrence_days' => $storeHabitData->occurrenceDays,
            'colour' => $storeHabitData->colour
        ]);
    }
}
