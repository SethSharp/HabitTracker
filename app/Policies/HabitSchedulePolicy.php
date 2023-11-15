<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\HabitSchedule;
use App\Domain\Iam\Models\User;

class HabitSchedulePolicy
{
    public function manage(User $user, HabitSchedule $habitSchedule): bool
    {
        return $user->id === $habitSchedule->user_id &&
            ! Carbon::now()->lte(Carbon::parse($habitSchedule->scheduled_completion));
    }
}
