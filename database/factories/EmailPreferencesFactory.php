<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EmailPreferencesFactory extends Factory
{
    public function definition(): array
    {
        return [
            'daily_reminder' => false,
        ];
    }
}
