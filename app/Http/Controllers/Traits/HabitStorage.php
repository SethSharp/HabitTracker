<?php

namespace App\Http\Controllers\Traits;

use App\Domain\Frequency\Enums\Frequency;

trait HabitStorage
{
    private function calculatedOccurrenceDays($data, $freq): string
    {
        return match ($freq) {
            Frequency::DAILY->value => json_encode($data['daily_config']),
            Frequency::WEEKLY->value => json_encode([(int)$data['weekly_config']]),
            Frequency::MONTHLY->value => json_encode([$data['monthly_config']]),
            default => now(),
        };
    }
}
