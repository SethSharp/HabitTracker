<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\Frequency;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

class HabitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'user_id' => User::factory()->create()->id,
            'description' => $this->faker->sentence(mt_rand(20, 25)),
            'frequency' => Frequency::DAILY
        ];
    }
}
