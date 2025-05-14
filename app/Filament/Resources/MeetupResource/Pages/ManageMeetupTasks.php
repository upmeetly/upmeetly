<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Enums\Countable;
use App\Enums\TaskStatus;
use App\Filament\Resources\MeetupResource;
use App\Models\Task;
use App\Queries\User\GetUserDefaultAvatarUrl;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ManageMeetupTasks extends ManageRelatedRecords
{
    protected static string $resource = MeetupResource::class;

    protected static string $relationship = 'tasks';

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function getNavigationLabel(): string
    {
        return trans_choice('Task|Tasks', Countable::MULTIPLE->value);
    }

    public function getTitle(): string
    {
        return trans_choice('Task|Tasks', Countable::MULTIPLE->value);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Grid::make(['default' => 1, 'md' => 12,])
                    ->schema([
                        Forms\Components\Grid::make(['default' => 1])
                            ->columnSpan(['default' => 'full', 'md' => 8])
                            ->schema([
                                Forms\Components\Section::make(__('Details'))
                                    ->description(__('Details of the task'))
                                    ->icon('heroicon-o-wrench-screwdriver')
                                    ->iconColor('primary')
                                    ->schema([
                                        Forms\Components\TextInput::make('title')
                                            ->label(__('Title'))
                                            ->required()
                                            ->maxLength(255),

                                        Forms\Components\RichEditor::make('description')
                                            ->label(__('Description')),
                                    ])
                            ]),

                        Forms\Components\Grid::make(['default' => 1])
                            ->columnSpan(['default' => 'full', 'md' => 4])
                            ->schema([
                                Forms\Components\Section::make(__('Status & Assignee'))
                                    ->description(__('Status and assignee of the task'))
                                    ->icon('heroicon-o-information-circle')
                                    ->iconColor('primary')
                                    ->schema([
                                        Forms\Components\Select::make('status')
                                            ->label(__('Status'))
                                            ->enum(TaskStatus::class)
                                            ->options(TaskStatus::class)
                                            ->required(),

                                        Forms\Components\Select::make('user_id')
                                            ->label(__('Assignee'))
                                            ->relationship(
                                                name: 'user',
                                                titleAttribute: 'name',
                                                modifyQueryUsing: fn (Builder $query) => $query->withinCurrentTeam()
                                            )
                                            ->searchable()
                                            ->preload(),
                                    ]),
                            ])
                    ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\Layout\Split::make([
                    Tables\Columns\TextColumn::make('status')
                        ->label(__('Status'))
                        ->badge()
                        ->sortable(),

                    Tables\Columns\ImageColumn::make('user.avatar')
                        ->defaultImageUrl(fn (Task $record) => $record->user ? resolve(GetUserDefaultAvatarUrl::class)->execute($record->user) : null)
                        ->grow(false)
                        ->circular(),

                    Tables\Columns\TextColumn::make('user.name')
                        ->weight(fn (mixed $state) => $state !== __('Unassigned') ? FontWeight::Bold : FontWeight::Light)
                        ->color(fn  (mixed $state) => $state !== __('Unassigned') ? 'primary' : 'gray')
                        ->default(__('Unassigned'))
                        ->searchable(),

                    Tables\Columns\TextColumn::make('title')
                        ->label(__('Title'))
                        ->searchable(),
                ]),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->slideOver()
                    ->modalWidth(MaxWidth::SevenExtraLarge),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->color('primary')
                        ->successNotificationTitle(__('Task updated successfully'))
                        ->slideOver()
                        ->modalWidth(MaxWidth::SevenExtraLarge),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
