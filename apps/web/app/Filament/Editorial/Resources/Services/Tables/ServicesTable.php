<?php

namespace App\Filament\Editorial\Resources\Services\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

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
                    ->options(\App\Enums\RecordLifecycleStatus::class),
                SelectFilter::make('group_service_family')
                    ->label('Family')
                    ->options(function () {
                        return \App\Models\Service::query()
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
                        \Filament\Schemas\Components\Select::make('target_service_id')
                            ->label('Target Service')
                            ->options(function (\App\Models\Service $record) {
                                return \App\Models\Service::where('_id', '!=', $record->_id)
                                    ->get()
                                    ->pluck('english_name', '_id');
                            })
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data, \App\Models\Service $record): void {
                        // In a real application, you'd update related records like Establishments/Therapists here
                        // to point to the new service ID.
                        
                        // Archive the old service
                        $record->status_record_lifecycle = \App\Enums\RecordLifecycleStatus::Archived;
                        $record->save();
                    }),
            ])
            ->bulkActions([
                \Filament\Tables\Actions\BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
