<?php

namespace App\Enums;

enum Frequency: string
{
    case DAILY = 'Daily';
    case WEEKLY = 'Weekly';
    case MONTHLY = 'Monthly';
}
