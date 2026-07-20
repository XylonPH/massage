<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum ReviewStatus: string implements HasLabel, HasColor
{
    case Pending = 'P';
    case Approved = 'A';
    case NeedsChanges = 'N';
    case Rejected = 'R';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Approved => 'Approved',
            self::NeedsChanges => 'Needs Changes',
            self::Rejected => 'Rejected',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Approved => 'success',
            self::NeedsChanges => 'danger',
            self::Rejected => 'danger',
        };
    }
}
