<?php

namespace App\Models\Article;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'article_id', 'article_body_id', 'language_id', 'revision_number', 'article_body',
    'word_count', 'reading_duration_visual', 'reading_duration_spoken', 'revision_note',
    'review_note', 'status_review', 'status_record_lifecycle', 'created_by_user_id',
    'created_at', 'submitted_at', 'submitted_by_user_id', 'reviewed_at', 'reviewed_by_user_id',
    'approved_at', 'approved_by_user_id',
])]
class ArticleRevision extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'article_revision';

    protected $primaryKey = '_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $revision): void {
            $revision->{$revision->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'language_id' => 'integer',
            'revision_number' => 'integer',
            'word_count' => 'integer',
            'reading_duration_visual' => 'integer',
            'reading_duration_spoken' => 'integer',
            'created_at' => 'datetime',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    /** @return array<string, mixed> */
    protected function sparseDefaults(): array
    {
        return [
            'revision_number' => 1,
            'revision_note' => null,
            'review_note' => null,
            'status_review' => 'P',
            'status_record_lifecycle' => 'ACT',
        ];
    }
}
