<?php

namespace App\Enums;

enum RecordLifecycleStatus: string
{
    case Draft = 'DRA';
    case Active = 'ACT';
    case Inactive = 'INA';
    case Archived = 'ARC';
    case Deleted = 'DEL';
    case Merged = 'MRG';
    case Duplicate = 'DUP';
    case Error = 'ERR';
    case Unknown = 'UNK';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Active => 'Active',
            self::Inactive => 'Inactive',
            self::Archived => 'Archived',
            self::Deleted => 'Deleted',
            self::Merged => 'Merged',
            self::Duplicate => 'Duplicate',
            self::Error => 'Error',
            self::Unknown => 'Unknown',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Draft => 'info',
            self::Active => 'success',
            self::Inactive => 'warning',
            self::Archived => 'gray',
            self::Deleted => 'danger',
            self::Merged => 'gray',
            self::Duplicate => 'warning',
            self::Error => 'danger',
            self::Unknown => 'gray',
        };
    }
}
