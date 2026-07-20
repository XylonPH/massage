<?php

namespace App\Filament\Editorial\Resources\Services\Tables;

use App\Enums\RecordLifecycleStatus;
use App\Models\Service;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('english_name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('service_slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('group_service_family')
                    ->label('Family')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status_record_lifecycle')
                    ->label('Status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_record_lifecycle')
                    ->label('Status')
                    ->options(RecordLifecycleStatus::class),
                SelectFilter::make('group_service_family')
                    ->label('Family')
                    ->options(function () {
                        return Service::query()
                            ->distinct()
                            ->pluck('group_service_family', 'group_service_family')
                            ->toArray();
                    }),
            ])
            ->actions([
                EditAction::make(),
                Action::make('merge')
                    ->label('Merge')
                    ->icon('heroicon-o-arrows-pointing-in')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Merge Service')
                    ->modalDescription('Select a target service to merge this one into. The current service will be archived.')
                    ->form([
                        Select::make('target_service_id')
                            ->label('Target Service')
                            ->options(function (Service $record) {
                                return Service::where('_id', '!=', $record->_id)
                                    ->get()
                                    ->pluck('english_name', '_id');
                            })
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data, Service $record): void {
                        // In a real application, you'd update related records like Establishments/Therapists here
                        // to point to the new service ID.

                        // Archive the old service
                        $record->status_record_lifecycle = RecordLifecycleStatus::Archived;
                        $record->save();
                    }),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
