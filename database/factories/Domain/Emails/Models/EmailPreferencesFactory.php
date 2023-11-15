<?php

namespace Database\Factories\Domain\Emails\Models;

use App\Domain\Emails\Models\EmailPreferences;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmailPreferencesFactory extends Factory
{
    protected $model = EmailPreferences::class;

    public function definition(): array
    {
        return [
            'daily_reminder' => false,
        ];
    }
}
