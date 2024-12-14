<?php

declare(strict_types=1);

namespace App\Enums;

enum Countable: int
{
    case SINGULAR = 1;
    case COUPLE = 2;
    case PLURAL = 3;
    case MULTIPLE = 10;
}
