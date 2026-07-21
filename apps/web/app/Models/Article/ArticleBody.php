<?php

namespace App\Models\Article;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable([
    'article_id', 'language_id', 'article_body', 'article_plain_text', 'word_count',
    'reading_duration_visual', 'reading_duration_spoken', 'translator_user_id_list',
    'source_article_body_id', 'method_translation', 'status_review',
    'status_record_lifecycle', 'created_by_user_id', 'updated_by_user_id',
    'reviewed_at', 'reviewed_by_user_id', 'approved_at', 'approved_by_user_id',
])]
class ArticleBody extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'article_body';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $body): void {
            $body->{$body->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'language_id' => 'integer',
            'word_count' => 'integer',
            'reading_duration_visual' => 'integer',
            'reading_duration_spoken' => 'integer',
            'reviewed_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    /** @return array<string, mixed> */
    protected function sparseDefaults(): array
    {
        return [
            'article_body' => '',
            'article_plain_text' => '',
            'word_count' => 0,
            'reading_duration_visual' => null,
            'reading_duration_spoken' => null,
            'translator_user_id_list' => [],
            'source_article_body_id' => null,
            'method_translation' => null,
            'status_review' => 'P',
            'status_record_lifecycle' => 'ACT',
        ];
    }
}
