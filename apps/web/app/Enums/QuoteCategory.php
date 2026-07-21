<?php

namespace App\Enums;

enum QuoteCategory: string
{
    case Wellness = 'WEL';
    case Relaxation = 'RLX';
    case Massage = 'MSG';
    case SelfCare = 'SLF';
    case Motivational = 'MOT';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Wellness => 'Wellness',
            self::Relaxation => 'Relaxation',
            self::Massage => 'Massage',
            self::SelfCare => 'Self-Care',
            self::Motivational => 'Motivational',
        };
    }
}
