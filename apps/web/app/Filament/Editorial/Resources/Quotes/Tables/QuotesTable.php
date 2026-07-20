<?php

namespace App\Filament\Editorial\Resources\Quotes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;

class QuotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('english_text')
                    ->label('Quote')
                    ->limit(50)
                    ->searchable(['quote_text.eng.text']),
                \Filament\Tables\Columns\TextColumn::make('attribution_name')
                    ->label('Attribution')
                    ->searchable(),
                \Filament\Tables\Columns\IconColumn::make('is_display_enabled')
                    ->label('Displayed')
                    ->boolean(),
                \Filament\Tables\Columns\TextColumn::make('status_review')
                    ->label('Status')
                    ->badge(),
                \Filament\Tables\Columns\TextColumn::make('display_start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('status_review')
                    ->options(\App\Enums\ReviewStatus::class),
                \Filament\Tables\Filters\TernaryFilter::make('is_display_enabled')
                    ->label('Display Enabled'),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
