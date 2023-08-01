<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
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
