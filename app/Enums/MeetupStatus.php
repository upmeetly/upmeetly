<?php

declare(strict_types=1);

namespace App\Enums;

enum MeetupStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CANCELLED = 'cancelled';
    case POSTPONED = 'postponed';
}
