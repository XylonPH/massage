<?php

namespace App\Filament\Editorial\Resources\Establishments\Pages;

use App\Filament\Editorial\Resources\Establishments\EstablishmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEstablishment extends CreateRecord
{
    protected static string $resource = EstablishmentResource::class;

    protected ?string $heading = 'Add Establishment';
}
