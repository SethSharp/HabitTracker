<?php

namespace App\Policies;

use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;

class HabitPolicy
{
    public function store(User $user, Habit $habit): bool
    {
        return $habit->user_id == $user->id;
    }

    public function update(User $user, Habit $habit): bool
    {
        return $habit->user_id == $user->id;
    }
}
