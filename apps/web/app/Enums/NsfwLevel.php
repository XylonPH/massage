<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum NsfwLevel: string implements HasLabel, HasColor
{
    case None = 'N';
    case Suggestive = 'S';
    case Mature = 'M';
    case Explicit = 'X';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::None => 'None',
            self::Suggestive => 'Suggestive',
            self::Mature => 'Mature',
            self::Explicit => 'Explicit',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::None => 'success',
            self::Suggestive => 'warning',
            self::Mature => 'danger',
            self::Explicit => 'danger',
        };
    }
}
