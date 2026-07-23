<?php

namespace App\Enums;

enum NsfwLevel: string
{
    case None = 'N';
    case Mild = 'M';
    case Sensitive = 'S';
    case Explicit = 'E';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::None => 'None',
            self::Mild => 'Mild',
            self::Sensitive => 'Sensitive',
            self::Explicit => 'Explicit',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::None => 'success',
            self::Mild => 'warning',
            self::Sensitive => 'warning',
            self::Explicit => 'danger',
        };
    }
}
