<?php

namespace App\Filament\Editorial\Resources\Establishments\Pages;

use App\Filament\Editorial\Resources\Establishments\EstablishmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEstablishments extends ListRecords
{
    protected static string $resource = EstablishmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
