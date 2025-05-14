<?php

declare(strict_types=1);

namespace App\Enums;

enum Countable: int
{
    case SINGULAR = 1;
    case DOUBLE = 2;
    case MULTIPLE = 10;
}
