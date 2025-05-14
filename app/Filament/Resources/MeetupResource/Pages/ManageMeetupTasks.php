<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Enums\Countable;
use App\Filament\Resources\MeetupResource;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
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
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
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
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->slideOver(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()->color('primary')->slideOver(),
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
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
