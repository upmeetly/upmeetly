<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Filament\Resources\MeetupResource;
use Filament\Resources\Pages\CreateRecord;

class CreateMeetup extends CreateRecord
{
    protected static string $resource = MeetupResource::class;
}
