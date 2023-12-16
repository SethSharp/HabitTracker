<?php

namespace App\Policies;

use App\Domain\Iam\Models\User;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class HabitSchedulePolicy
{
    public function manage(User $user, HabitSchedule $habitSchedule): bool
    {
        return $user->id === $habitSchedule->user_id;
    }
}
