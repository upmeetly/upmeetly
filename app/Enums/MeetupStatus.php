<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum MeetupStatus: string implements HasColor, HasLabel
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case PAST = 'past';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::SCHEDULED => __('Upcoming'),
            self::PAST => __('Past'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SCHEDULED => 'success',
            self::PAST => 'danger',
        };
    }
}
