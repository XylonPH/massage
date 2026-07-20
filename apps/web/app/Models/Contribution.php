<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'type_contribution', 'target_collection', 'target_record_id',
    'submitted_by_user_id', 'proposed_data',
    'type_establishment_relationship', 'type_practitioner_relationship',
    'is_workspace_access_requested',
    'relationship_note', 'status_contribution', 'submitted_at',
    'reviewed_at', 'reviewer_user_id', 'decision_note',
])]
class Contribution extends Model
{
    protected $connection = 'mongodb';

    protected $table = 'contribution_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $contribution): void {
            $contribution->{$contribution->getKeyName()} ??= Str::random(16);
            $contribution->status_contribution ??= 'PND';
        });
    }

    protected function casts(): array
    {
        return [
            'proposed_data' => 'array',
            'is_workspace_access_requested' => 'boolean',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }
}
