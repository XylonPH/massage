<?php

namespace App\Filament\Editorial\Resources\Quotes\Tables;

use App\Enums\ReviewStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class QuotesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('english_text')
                    ->label('Quote')
                    ->limit(50)
                    ->searchable(['quote_text.eng.text']),
                TextColumn::make('attribution_name')
                    ->label('Attribution')
                    ->searchable(),
                IconColumn::make('is_display_enabled')
                    ->label('Displayed')
                    ->boolean(),
                TextColumn::make('status_review')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('display_start_date')
                    ->label('Start Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status_review')
                    ->options(ReviewStatus::class),
                TernaryFilter::make('is_display_enabled')
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
