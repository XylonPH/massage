<?php

namespace App\Filament\Editorial\Resources\Services\Schemas;

use App\Enums\RecordLifecycleStatus;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Select;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Textarea;
use Filament\Schemas\Components\TextInput;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('Translations')->tabs([
                    Tab::make('English')->schema([
                        TextInput::make('english_name')
                            ->label('Primary Name')
                            ->required()
                            ->maxLength(150),
                        Textarea::make('english_short_description')
                            ->label('Short Description')
                            ->maxLength(300),
                        Textarea::make('english_overview')
                            ->label('Overview')
                            ->maxLength(2000),
                    ]),
                    Tab::make('Chinese (Traditional)')->schema([
                        TextInput::make('chinese_name')
                            ->label('Primary Name')
                            ->maxLength(150),
                        Textarea::make('chinese_short_description')
                            ->label('Short Description')
                            ->maxLength(300),
                        Textarea::make('chinese_overview')
                            ->label('Overview')
                            ->maxLength(2000),
                    ]),
                ]),
                Section::make('Configuration')->schema([
                    TextInput::make('service_slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),
                ]),
                Section::make('Classification')->schema([
                    TextInput::make('group_service_sector')
                        ->label('Sector')
                        ->maxLength(100),
                    TextInput::make('group_service_domain')
                        ->label('Domain')
                        ->maxLength(100),
                    TextInput::make('group_service_family')
                        ->label('Family')
                        ->required()
                        ->maxLength(100),
                ]),
                Section::make('Status')->schema([
                    Select::make('status_record_lifecycle')
                        ->label('Record Lifecycle Status')
                        ->options(RecordLifecycleStatus::class)
                        ->default('ACT')
                        ->required(),
                ]),
            ]);
    }
}
