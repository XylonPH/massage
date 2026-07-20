<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum RecordLifecycleStatus: string implements HasLabel, HasColor
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

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Active => 'success',
            self::Inactive => 'warning',
            self::Archived => 'gray',
            self::Deleted => 'danger',
        };
    }
}
