<?php

namespace App\Models\Article;

use App\Models\Concerns\HasSparseDefaults;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

#[Fillable(['tag_title', 'tag_slug', 'language_original_id', 'usage_count', 'status_record_lifecycle', 'created_by_user_id', 'updated_by_user_id'])]
class Tag extends Model
{
    use HasSparseDefaults;

    protected $connection = 'mongodb';

    protected $table = 'tag_main';

    protected $primaryKey = '_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function booted(): void
    {
        static::creating(function (self $tag): void {
            $tag->{$tag->getKeyName()} ??= Str::random(16);
        });
    }

    protected function casts(): array
    {
        return [
            'language_original_id' => 'integer',
            'usage_count' => 'integer',
        ];
    }

    /** @return array<string, mixed> */
    protected function sparseDefaults(): array
    {
        return [
            'usage_count' => 0,
            'status_record_lifecycle' => 'ACT',
        ];
    }

    public function localized(string $field, string $locale = 'eng'): string
    {
        $values = $this->getAttribute($field);

        return is_array($values) ? (string) ($values[$locale]['text'] ?? $values['eng']['text'] ?? '') : '';
    }
}
