<?php

namespace App\Http\Controllers\Traits;

use App\Enums\Frequency;

trait HabitStorageTrait
{
    private function calculatedOccurrenceDays($data, $freq): string
    {
        switch ($freq) {
            case Frequency::DAILY->value:
                return json_encode($data['daily_config']);
            case Frequency::WEEKLY->value:
                return json_encode([(int)$data['weekly_config']]);
            case Frequency::MONTHLY->value:
                return json_encode([$data['monthly_config']]);
            default:
                return now();
        }
    }
}
