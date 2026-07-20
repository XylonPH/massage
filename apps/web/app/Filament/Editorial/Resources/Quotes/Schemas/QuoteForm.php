<?php

namespace App\Filament\Editorial\Resources\Quotes\Schemas;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Enums\ReviewStatus;
use Filament\Schemas\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Components\Toggle;
use Filament\Schemas\Schema;

class QuoteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Quote Content')->schema([
                    Textarea::make('english_text')
                        ->label('Quote Text (English)')
                        ->required()
                        ->maxLength(500),
                    TextInput::make('attribution_name')
                        ->label('Attribution Name')
                        ->maxLength(150),
                    TextInput::make('source_title')
                        ->label('Source Title')
                        ->maxLength(200),
                    TextInput::make('source_url')
                        ->label('Source URL')
                        ->url()
                        ->maxLength(500),
                ]),
                Section::make('Classification')->schema([
                    TextInput::make('language_original_id')
                        ->label('Original Language ID')
                        ->numeric()
                        ->default(3049), // English
                    Select::make('type_quote_category')
                        ->label('Quote Category')
                        ->multiple()
                        ->options(QuoteCategory::class),
                ]),
                Section::make('Scheduling & Display')->schema([
                    Toggle::make('is_display_enabled')
                        ->label('Display Enabled')
                        ->default(true),
                    DatePicker::make('display_start_date')
                        ->label('Display Start Date'),
                    DatePicker::make('display_end_date')
                        ->label('Display End Date'),
                ]),
                Section::make('Status')->schema([
                    Select::make('status_review')
                        ->label('Review Status')
                        ->options(ReviewStatus::class)
                        ->default('P')
                        ->required(),
                    Select::make('level_nsfw')
                        ->label('NSFW Level')
                        ->options(NsfwLevel::class)
                        ->default('N')
                        ->required(),
                    Select::make('status_record_lifecycle')
                        ->label('Record Lifecycle Status')
                        ->options(RecordLifecycleStatus::class)
                        ->default('ACT')
                        ->required(),
                ]),
            ]);
    }
}
