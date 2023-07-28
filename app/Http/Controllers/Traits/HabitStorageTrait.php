<?php

namespace App\Http\Controllers\Traits;

use DateTime;
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
                $date = DateTime::createFromFormat('Y-n-j', $data['monthly_config']);
                return json_encode([$date->format('Y-m-d')]);
            default:
                return now();
        }
    }
}
