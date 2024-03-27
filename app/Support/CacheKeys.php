<?php

namespace App\Support;

use App\Domain\Iam\Models\User;

class CacheKeys
{
    public static function weeklyHabitLog(User $user): string
    {
        return 'weekly-habit-log-' . $user->id;
    }

    public static function scheduledHabitsForTheMonth(User $user, string $month): string
    {
        return 'monthly-habit-scheduled-' . $user->id . '-' . $month;
    }
}
