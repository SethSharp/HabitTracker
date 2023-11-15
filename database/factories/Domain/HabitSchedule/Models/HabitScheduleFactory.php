<?php

namespace Database\Factories\Domain\HabitSchedule\Models;

use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;
use App\Domain\HabitSchedule\Models\HabitSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitScheduleFactory extends Factory
{
    protected $model = HabitSchedule::class;

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
