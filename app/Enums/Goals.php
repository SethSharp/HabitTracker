<?php

namespace App\Enums;

enum Goals: int
{
    case NONE = 0;
    case DAILY = 1;
    case WEEKLY = 2;
    case MONTHLY = 3;
}
