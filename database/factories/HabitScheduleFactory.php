<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'habit_id' => function () {
                return Habit::factory()->create()->id;
            },
            'scheduled_completion' => now()->addDay(),
        ];
    }
}
