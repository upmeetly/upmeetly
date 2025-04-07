<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Filament\Resources\MeetupResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMeetup extends ViewRecord
{
    protected static string $resource = MeetupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
