<?php

namespace App\Filament\Editorial\Resources\Quotes;

use App\Filament\Editorial\Resources\Quotes\Pages\CreateQuote;
use App\Filament\Editorial\Resources\Quotes\Pages\EditQuote;
use App\Filament\Editorial\Resources\Quotes\Pages\ListQuotes;
use App\Filament\Editorial\Resources\Quotes\Schemas\QuoteForm;
use App\Filament\Editorial\Resources\Quotes\Tables\QuotesTable;
use App\Models\Quote;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static \UnitEnum|string|null $navigationGroup = 'Content';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::ChatBubbleBottomCenterText;

    public static function form(Schema $schema): Schema
    {
        return QuoteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuotesTable::configure($table);
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
            'index' => ListQuotes::route('/'),
            'create' => CreateQuote::route('/create'),
            'edit' => EditQuote::route('/{record}/edit'),
        ];
    }
}
