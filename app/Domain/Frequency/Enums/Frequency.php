<?php

namespace App\Domain\Frequency\Enums;

enum Frequency: string
{
    case DAILY = 'Daily';
    case WEEKLY = 'Weekly';
    case MONTHLY = 'Monthly';
}
