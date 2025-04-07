<?php

declare(strict_types=1);

namespace App\Enums;

enum MemberStatus: string
{
    case NONE = 'none';
    case REQUESTED = 'requested';
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case BANNED = 'banned';
    case LEFT = 'left';
}
