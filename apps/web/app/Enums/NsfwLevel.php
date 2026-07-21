<?php

namespace App\Enums;

enum NsfwLevel: string
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

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::None => 'success',
            self::Suggestive => 'warning',
            self::Mature => 'danger',
            self::Explicit => 'danger',
        };
    }
}
