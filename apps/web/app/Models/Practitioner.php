<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'practitioner_name', 'practitioner_slug', 'short_description', 'biography',
    'language_original_id', 'type_practice_setting', 'type_specialty_focus',
    'target_client_focus', 'status_therapist_practice', 'is_claimed',
    'visibility_scope', 'status_record_lifecycle',
])]
class Practitioner extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'practitioner_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $practitioner): void {
            $practitioner->{$practitioner->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'practitioner_name' => 'array',
            'practitioner_slug' => 'array',
            'short_description' => 'array',
            'biography' => 'array',
            'type_practice_setting' => 'array',
            'type_specialty_focus' => 'array',
            'target_client_focus' => 'array',
            'is_claimed' => 'boolean',
        ];
    }
}
