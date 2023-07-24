<?php

namespace App\Http;

use App\Models\User;

class CacheKeys
{
    public static function weeklyHabitLog(User $user): string
    {
        return 'weekly-habit-log-' . $user->id;
    }
}
