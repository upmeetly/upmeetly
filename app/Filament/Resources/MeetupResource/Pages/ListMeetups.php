<?php

declare(strict_types=1);

namespace App\Filament\Resources\MeetupResource\Pages;

use App\Enums\DateFormat;
use App\Enums\MeetupStatus;
use App\Filament\Resources\MeetupResource;
use App\Models\Meetup;
use Exception;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ListMeetups extends ListRecords
{
    protected static string $resource = MeetupResource::class;

    public function getTabs(): array
    {
        return array_merge(
            collect(MeetupStatus::cases())
                ->mapWithKeys(fn (MeetupStatus $status): array => [
                    $status->value => Tab::make($status->getLabel())
                        ->icon($status->getIcon())
                        ->modifyQueryUsing(fn (Builder $query): Builder => $query->where('status', $status)),
                ])->toArray(), [
                    'all' => Tab::make(__('All'))
                        ->badgeColor('gray')
                        ->icon('heroicon-o-rectangle-stack'),
                ]);
    }

    /**
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable(),

                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('Starts at'))
                    ->since()
                    ->dateTooltip(DateFormat::STANDARD->value)
                    ->sortable(),

                Tables\Columns\TextColumn::make('ends_at')
                    ->label(__('To'))
                    ->since()
                    ->dateTooltip(DateFormat::STANDARD->value)
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->label(__('Manage'))
                        ->color('primary')
                        ->icon('heroicon-o-rocket-launch'),

                    Tables\Actions\ActionGroup::make([
                        Tables\Actions\DeleteAction::make()
                            ->label(__('Delete')),
                    ])->dropdown(false),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->icon('heroicon-o-plus')
                ->label(__('Create a meetup'))
                ->slideOver()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['user_id'] = auth()->id();

                    return $data;
                })
                ->successRedirectUrl(fn (Meetup $meetup): string => ViewMeetup::getUrl(
                    parameters: [$meetup],
                    tenant: $meetup->team)
                ),
        ];
    }
}
