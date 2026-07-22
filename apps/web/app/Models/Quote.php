<?php

namespace App\Models;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Support\Article\ArticleLanguage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use MongoDB\Laravel\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';

    protected $table = 'quote_main';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        '_id',
        'quote_text',
        'language_original_id',
        'type_quote_category',
        'attribution_label',
        'source_title',
        'source_url',
        'visibility_scope',
        'level_nsfw',
        'status_record_lifecycle',
        'published_at',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    protected $casts = [
        'language_original_id' => 'integer',
        'published_at' => 'datetime',
        'level_nsfw' => NsfwLevel::class,
        'status_record_lifecycle' => RecordLifecycleStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::random(16);
            }
            if (empty($model->published_at)) {
                $model->published_at = now();
            }
        });
    }

    /**
     * Scope query to eligible public quotes for rotation.
     */
    public function scopeEligiblePublic(Builder $query): Builder
    {
        return $query
            ->where('status_record_lifecycle', RecordLifecycleStatus::Active->value)
            ->where(function (Builder $q): void {
                $q->whereNull('visibility_scope')->orWhere('visibility_scope', 'PUB');
            })
            ->where(function (Builder $q): void {
                $q->whereNull('level_nsfw')->orWhere('level_nsfw', NsfwLevel::None->value);
            })
            ->where(function (Builder $q): void {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Returns the Category Enum.
     */
    public function getCategoryEnumAttribute(): ?QuoteCategory
    {
        return QuoteCategory::tryFrom((string) $this->type_quote_category);
    }

    /**
     * Get the original language 3-letter ISO key (e.g. 'eng', 'fil', 'ceb', 'kor', 'spa', 'zho').
     */
    public function getOriginalLanguageKeyAttribute(): string
    {
        $id = (int) ($this->language_original_id ?? 3049);
        $map = [
            3049 => 'eng',
            3600 => 'fil',
            1458 => 'ceb',
            7142 => 'kor',
            12559 => 'spa',
            17097 => 'zho',
        ];

        return $map[$id] ?? ArticleLanguage::keyForId($id);
    }

    /**
     * Get the original text stored for the original language_id.
     */
    public function getOriginalTextAttribute(): ?string
    {
        $key = $this->original_language_key;
        if (isset($this->quote_text[$key]['text'])) {
            return $this->quote_text[$key]['text'];
        }

        // Fallback: first available text in array
        if (is_array($this->quote_text)) {
            foreach ($this->quote_text as $langData) {
                if (! empty($langData['text'])) {
                    return $langData['text'];
                }
            }
        }

        return null;
    }

    /**
     * Get quote text for a specific locale, falling back gracefully.
     *
     * @return array{text: string, is_original: bool, language_key: string, original_text: string|null, original_language_key: string}
     */
    public function getResolvedDisplay(?string $requestedLocale = null): array
    {
        $requestedLocale ??= app()->getLocale();

        $originalKey = $this->original_language_key;
        $originalText = $this->original_text ?? '';

        // Standardize locale string if zho-hans or zho-hant
        $langKey = match (strtolower($requestedLocale)) {
            'zh-cn', 'zho-hans', 'zh-hans' => 'zho-hans',
            'zh-tw', 'zho-hant', 'zh-hant' => 'zho-hant',
            default => strtolower(substr($requestedLocale, 0, 3)),
        };

        // 1. Direct match on requested locale in quote_text
        if (isset($this->quote_text[$langKey]['text']) && filled($this->quote_text[$langKey]['text'])) {
            $isOriginal = ($langKey === $originalKey || str_starts_with($langKey, $originalKey));

            return [
                'text' => $this->quote_text[$langKey]['text'],
                'is_original' => $isOriginal,
                'language_key' => $langKey,
                'original_text' => $originalText,
                'original_language_key' => $originalKey,
            ];
        }

        // 2. Check general language key if requested key is subvariant (e.g. zho for zho-hans)
        if (str_starts_with($langKey, 'zho') && isset($this->quote_text['zho']['text'])) {
            return [
                'text' => $this->quote_text['zho']['text'],
                'is_original' => str_starts_with($originalKey, 'zho'),
                'language_key' => 'zho',
                'original_text' => $originalText,
                'original_language_key' => $originalKey,
            ];
        }

        // 3. Fallback: Display original text with original language indicated
        return [
            'text' => $originalText,
            'is_original' => true,
            'language_key' => $originalKey,
            'original_text' => $originalText,
            'original_language_key' => $originalKey,
        ];
    }
}
