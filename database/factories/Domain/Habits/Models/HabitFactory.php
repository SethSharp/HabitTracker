<?php

namespace Database\Factories\Domain\Habits\Models;

use App\Enums\Frequency;
use App\Domain\Iam\Models\User;
use App\Domain\Habits\Models\Habit;
use Illuminate\Database\Eloquent\Factories\Factory;

class HabitFactory extends Factory
{
    protected $model = Habit::class;

    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'name' => $this->faker->title(),
            'description' => $this->faker->sentence(mt_rand(10, 15)),
            'frequency' => Frequency::DAILY,
            'occurrence_days' => '[2,3,4]',
        ];
    }
}