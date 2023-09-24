<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\HabitSchedule;

class HabitSchedulePolicy
{
    public function manage(User $user, HabitSchedule $habitSchedule): bool
    {
        return $user->id === $habitSchedule->user_id &&
            Carbon::now()->toDateString() === Carbon::parse($habitSchedule->scheduled_completion)->toDateString();
    }
}
