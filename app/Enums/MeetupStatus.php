<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MeetupStatus: string implements HasColor, HasIcon, HasLabel
{
    case DRAFT = 'draft';
    case SCHEDULED = 'scheduled';
    case IN_PROGRESS = 'in_progress';
    case PAST = 'past';
    case CANCELED = 'canceled';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::DRAFT => __('Draft'),
            self::SCHEDULED => __('Upcoming'),
            self::IN_PROGRESS => __('In Progress'),
            self::PAST => __('Past'),
            self::CANCELED => __('Canceled'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::SCHEDULED => 'success',
            self::IN_PROGRESS => 'warning',
            self::PAST, self::CANCELED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::DRAFT => 'heroicon-o-document-text',
            self::SCHEDULED => 'heroicon-o-calendar',
            self::IN_PROGRESS => 'heroicon-o-presentation-chart-line',
            self::PAST => 'heroicon-o-clock',
            self::CANCELED => 'heroicon-o-x-circle',
        };
    }
}
