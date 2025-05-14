<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Enums\DateFormat;
use App\Filament\Resources\MeetupResource;
use App\Models\Meetup;
use Filament\Actions;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Enums\FontWeight;

/**
 * @property Meetup $record
 */
class ViewMeetup extends ViewRecord
{
    protected static string $resource = MeetupResource::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    public static function getNavigationLabel(): string
    {
        return __('Overview');
    }

    public function getBreadcrumb(): string
    {
        return __('Overview');
    }

    public function getTitle(): string
    {
        return __('Overview');
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(__('Details'))
                    ->description(__('Details of the meetup'))
                    ->icon('heroicon-m-information-circle')
                    ->iconColor('primary')
                    ->columns()
                    ->schema([
                        Infolists\Components\TextEntry::make('title')
                            ->label(__('Title')),

                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->label(__('Status')),

                        Infolists\Components\TextEntry::make('description')
                            ->label(__('Description'))
                            ->html()
                            ->columnSpanFull(),

                        Infolists\Components\TextEntry::make('starts_at')
                            ->label(__('Starts at'))
                            ->icon('heroicon-o-calendar')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull()
                            ->dateTime(DateFormat::STANDARD->value),

                        Infolists\Components\TextEntry::make('ends_at')
                            ->label(__('Ends at'))
                            ->icon('heroicon-o-calendar')
                            ->weight(FontWeight::Bold)
                            ->columnSpanFull()
                            ->dateTime(DateFormat::STANDARD->value),
                    ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->slideOver(),
        ];
    }
}
