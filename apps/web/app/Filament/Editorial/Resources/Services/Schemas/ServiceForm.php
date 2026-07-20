<?php

namespace App\Filament\Editorial\Resources\Services\Schemas;

use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Tabs::make('Translations')->tabs([
                    \Filament\Schemas\Components\Tabs\Tab::make('English')->schema([
                        \Filament\Schemas\Components\TextInput::make('english_name')
                            ->label('Primary Name')
                            ->required()
                            ->maxLength(150),
                        \Filament\Schemas\Components\Textarea::make('english_short_description')
                            ->label('Short Description')
                            ->maxLength(300),
                        \Filament\Schemas\Components\Textarea::make('english_overview')
                            ->label('Overview')
                            ->maxLength(2000),
                    ]),
                    \Filament\Schemas\Components\Tabs\Tab::make('Chinese (Traditional)')->schema([
                        \Filament\Schemas\Components\TextInput::make('chinese_name')
                            ->label('Primary Name')
                            ->maxLength(150),
                        \Filament\Schemas\Components\Textarea::make('chinese_short_description')
                            ->label('Short Description')
                            ->maxLength(300),
                        \Filament\Schemas\Components\Textarea::make('chinese_overview')
                            ->label('Overview')
                            ->maxLength(2000),
                    ]),
                ]),
                \Filament\Schemas\Components\Section::make('Configuration')->schema([
                    \Filament\Schemas\Components\TextInput::make('service_slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(100),
                ]),
                \Filament\Schemas\Components\Section::make('Classification')->schema([
                    \Filament\Schemas\Components\TextInput::make('group_service_sector')
                        ->label('Sector')
                        ->maxLength(100),
                    \Filament\Schemas\Components\TextInput::make('group_service_domain')
                        ->label('Domain')
                        ->maxLength(100),
                    \Filament\Schemas\Components\TextInput::make('group_service_family')
                        ->label('Family')
                        ->required()
                        ->maxLength(100),
                ]),
                \Filament\Schemas\Components\Section::make('Status')->schema([
                    \Filament\Schemas\Components\Select::make('status_record_lifecycle')
                        ->label('Record Lifecycle Status')
                        ->options(\App\Enums\RecordLifecycleStatus::class)
                        ->default('ACT')
                        ->required(),
                ]),
            ]);
    }
}
