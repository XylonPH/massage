<?php

namespace App\Filament\Editorial\Resources\Establishments;

use App\Filament\Editorial\Resources\Establishments\Pages\CreateEstablishment;
use App\Filament\Editorial\Resources\Establishments\Pages\EditEstablishment;
use App\Filament\Editorial\Resources\Establishments\Pages\ListEstablishments;
use App\Filament\Editorial\Resources\Establishments\Schemas\EstablishmentForm;
use App\Filament\Editorial\Resources\Establishments\Tables\EstablishmentsTable;
use App\Models\Establishment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EstablishmentResource extends Resource
{
    protected static ?string $model = Establishment::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Directory';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    public static function form(Schema $schema): Schema
    {
        return EstablishmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EstablishmentsTable::configure($table);
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
            'index' => ListEstablishments::route('/'),
            'create' => CreateEstablishment::route('/create'),
            'edit' => EditEstablishment::route('/{record}/edit'),
        ];
    }
}
