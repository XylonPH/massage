<?php

namespace App\Filament\Editorial\Resources\Quotes\Schemas;

use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Quote Content')->schema([
                    \Filament\Schemas\Components\Textarea::make('english_text')
                        ->label('Quote Text (English)')
                        ->required()
                        ->maxLength(500),
                    \Filament\Schemas\Components\TextInput::make('attribution_name')
                        ->label('Attribution Name')
                        ->maxLength(150),
                    \Filament\Schemas\Components\TextInput::make('source_title')
                        ->label('Source Title')
                        ->maxLength(200),
                    \Filament\Schemas\Components\TextInput::make('source_url')
                        ->label('Source URL')
                        ->url()
                        ->maxLength(500),
                ]),
                \Filament\Schemas\Components\Section::make('Classification')->schema([
                    \Filament\Schemas\Components\TextInput::make('language_original_id')
                        ->label('Original Language ID')
                        ->numeric()
                        ->default(3049), // English
                    \Filament\Schemas\Components\Select::make('type_quote_category')
                        ->label('Quote Category')
                        ->multiple()
                        ->options(\App\Enums\QuoteCategory::class),
                ]),
                \Filament\Schemas\Components\Section::make('Scheduling & Display')->schema([
                    \Filament\Schemas\Components\Toggle::make('is_display_enabled')
                        ->label('Display Enabled')
                        ->default(true),
                    \Filament\Schemas\Components\DatePicker::make('display_start_date')
                        ->label('Display Start Date'),
                    \Filament\Schemas\Components\DatePicker::make('display_end_date')
                        ->label('Display End Date'),
                ]),
                \Filament\Schemas\Components\Section::make('Status')->schema([
                    \Filament\Schemas\Components\Select::make('status_review')
                        ->label('Review Status')
                        ->options(\App\Enums\ReviewStatus::class)
                        ->default('P')
                        ->required(),
                    \Filament\Schemas\Components\Select::make('level_nsfw')
                        ->label('NSFW Level')
                        ->options(\App\Enums\NsfwLevel::class)
                        ->default('N')
                        ->required(),
                    \Filament\Schemas\Components\Select::make('status_record_lifecycle')
                        ->label('Record Lifecycle Status')
                        ->options(\App\Enums\RecordLifecycleStatus::class)
                        ->default('ACT')
                        ->required(),
                ]),
            ]);
    }
}
