<?php

namespace App\Domain\Goals\Enums;

enum Goals: int
{
    case NONE = 0;
    case WEEKLY = 1;
    case MONTHLY = 2;
}
