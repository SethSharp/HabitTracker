<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Habit;

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

    public function restore(User $user, int $id): bool
    {
        ray($user, $id);
        return $id == $user->id;
    }
}
