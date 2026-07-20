<?php

namespace App\Filament\Editorial\Resources\Establishments\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\File;

class EstablishmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('english_name')
                    ->label('Name')
                    ->searchable(['display_name.eng', 'display_name.zho'])
                    ->sortable(),
                TextColumn::make('type_spa')
                    ->label('Spa Type')
                    ->formatStateUsing(fn (string $state): string => self::getTaxonomyLabel('type_spa', $state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('level_spa_market')
                    ->label('Market Class')
                    ->formatStateUsing(fn (?string $state): ?string => $state ? self::getTaxonomyLabel('level_spa_market', $state) : null)
                    ->sortable(),
                TextColumn::make('status_establishment')
                    ->label('Establishment Status')
                    ->formatStateUsing(fn (string $state): string => self::getTaxonomyLabel('status_establishment', $state))
                    ->badge()
                    ->sortable(),
                TextColumn::make('status_record_lifecycle')
                    ->label('Lifecycle Status')
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_record_lifecycle')
                    ->label('Lifecycle Status')
                    ->options(\App\Enums\RecordLifecycleStatus::class),
                SelectFilter::make('type_spa')
                    ->label('Spa Type')
                    ->options(self::getTaxonomyOptions('type_spa')),
                SelectFilter::make('status_establishment')
                    ->label('Establishment Status')
                    ->options(self::getTaxonomyOptions('status_establishment')),
            ])
            ->actions([
                EditAction::make(),
                Action::make('merge')
                    ->label('Merge')
                    ->icon('heroicon-o-arrows-pointing-in')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Merge Establishment')
                    ->modalDescription('Select a target establishment to merge this one into. The current establishment will be archived.')
                    ->form([
                        \Filament\Schemas\Components\Select::make('target_establishment_id')
                            ->label('Target Establishment')
                            ->options(function (\App\Models\Establishment $record) {
                                return \App\Models\Establishment::where('_id', '!=', $record->_id)
                                    ->get()
                                    ->pluck('english_name', '_id');
                            })
                            ->required()
                            ->searchable(),
                    ])
                    ->action(function (array $data, \App\Models\Establishment $record): void {
                        // In a real application, you'd update related records like Therapists here
                        // to point to the new establishment ID.
                        
                        // Archive the old establishment
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

    private static function getTaxonomyOptions(string $fieldName): array
    {
        $path = base_path('data/taxonomy/massage_nexus/establishment_classification.json');
        
        if (!File::exists($path)) {
            return [];
        }

        $data = json_decode(File::get($path), true);
        
        foreach ($data as $field) {
            if ($field['field_name'] === $fieldName) {
                $options = [];
                foreach ($field['field_option'] ?? [] as $option) {
                    $options[$option['option_code']] = $option['option_label'];
                }
                return $options;
            }
        }
        
        return [];
    }

    private static function getTaxonomyLabel(string $fieldName, string $code): string
    {
        $options = self::getTaxonomyOptions($fieldName);
        return $options[$code] ?? $code;
    }
}
