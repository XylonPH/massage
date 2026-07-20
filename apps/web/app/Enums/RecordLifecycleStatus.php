<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RecordLifecycleStatus: string implements HasColor, HasLabel
{
    case Active = 'ACT';
    case Inactive = 'INA';
    case Archived = 'ARC';
    case Deleted = 'DEL';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Archived => 'Archived',
            self::Deleted => 'Deleted',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'warning',
            self::Archived => 'gray',
            self::Deleted => 'danger',
        };
    }
}
