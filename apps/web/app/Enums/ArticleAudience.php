<?php

namespace App\Enums;

enum ArticleAudience: string
{
    case General = 'G';
    case Client = 'C';
    case Practitioner = 'P';
    case Owner = 'O';
    case Caregiver = 'V';
    case Traveler = 'T';

    public function label(): string
    {
        return match ($this) {
            self::General => 'General',
            self::Client => 'Client',
            self::Practitioner => 'Practitioner',
            self::Owner => 'Owner',
            self::Caregiver => 'Caregiver',
            self::Traveler => 'Traveler',
        };
    }

    public function slug(): string
    {
        return strtolower($this->label());
    }

    public static function fromSlug(string $slug): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->slug() === $slug) {
                return $case;
            }
        }

        return null;
    }
}
