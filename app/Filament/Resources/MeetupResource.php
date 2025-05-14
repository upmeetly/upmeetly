<?php

declare(strict_types=1);

namespace App\Filament\Resources;

use App\Filament\Resources\MeetupResource\Pages;
use App\Models\Meetup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MeetupResource extends Resource
{
    protected static ?string $model = Meetup::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public static function form(Form $form): Form
    {
        return $form
            ->columns()
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->label(__('Title'))
                    ->columnSpanFull()
                    ->required()
                    ->maxLength(255),

                Forms\Components\RichEditor::make('description')
                    ->label(__('Description'))
                    ->rules(['nullable', 'sometimes', 'string', 'max:65535'])
                    ->columnSpanFull()
                    ->disableToolbarButtons([
                        'attachFiles',
                    ]),

                Forms\Components\DateTimePicker::make('starts_at')
                    ->label(__('Starts at'))
                    ->hiddenOn('create')
                    ->required()
                    ->default(now()->addDay()->setTime(10, 0)),

                Forms\Components\DateTimePicker::make('ends_at')
                    ->label(__('Ends at'))
                    ->hiddenOn('create')
                    ->required()
                    ->default(now()->addDay()->setTime(14, 0)),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewMeetup::class,
            Pages\ManageMeetupTasks::class,
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetups::route('/'),
            'view' => Pages\ViewMeetup::route('/{record}'),
            'tasks' => Pages\ManageMeetupTasks::route('/{record}/tasks'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with([
                'team',
            ])
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
