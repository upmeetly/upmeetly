<?php

declare(strict_types=1);

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum TaskStatus: string implements HasLabel, HasColor, HasIcon
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';

    public function getLabel(): string
    {
        return match ($this) {
            self::TODO => __('To Do'),
            self::IN_PROGRESS => __('In Progress'),
            self::COMPLETED => __('Completed'),
            self::REJECTED => __('Rejected'),
        };
    }

    /**
     * @inheritDoc
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TODO => 'gray',
            self::IN_PROGRESS => 'warning',
            self::COMPLETED => 'success',
            self::REJECTED => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::TODO => 'heroicon-o-clipboard-document-list',
            self::IN_PROGRESS => 'heroicon-o-arrow-path',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::REJECTED => 'heroicon-o-x-circle',
        };
    }
}
