<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Domain\Iam\Models\User;
use App\Domain\HabitSchedule\Models\HabitSchedule;

class HabitSchedulePolicy
{
    public function manage(User $user, HabitSchedule $habitSchedule): bool
    {
        return $user->id === $habitSchedule->user_id &&
            ! Carbon::now()->lte(Carbon::parse($habitSchedule->scheduled_completion));
    }
}
