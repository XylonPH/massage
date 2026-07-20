<?php

namespace App\Filament\Editorial\Resources\Services;

use App\Filament\Editorial\Resources\Services\Pages\CreateService;
use App\Filament\Editorial\Resources\Services\Pages\EditService;
use App\Filament\Editorial\Resources\Services\Pages\ListServices;
use App\Filament\Editorial\Resources\Services\Schemas\ServiceForm;
use App\Filament\Editorial\Resources\Services\Tables\ServicesTable;
use App\Models\Service;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Directory';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreateService::route('/create'),
            'edit' => EditService::route('/{record}/edit'),
        ];
    }
}
