<?php

namespace App\Http\Requests\Article;

use App\Enums\ArticleAudience;
use App\Enums\ArticleCategory;
use App\Support\Article\ArticleContent;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveArticleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if ($this->has('article_body')) {
            $this->merge([
                'article_body' => app(ArticleContent::class)->sanitize((string) $this->input('article_body')),
            ]);
        }
    }

    public function authorize(): bool
    {
        return $this->user()?->isActive() === true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'article_title' => ['required', 'string', 'max:75'],
            'article_slug' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'short_description' => ['required', 'string', 'max:255'],
            'type_article_category' => ['required', Rule::enum(ArticleCategory::class)],
            'target_audience' => ['required', Rule::enum(ArticleAudience::class)],
            'level_nsfw' => ['required', Rule::in(['N', 'M', 'S', 'E'])],
            'tags' => ['nullable', 'string', 'max:500'],
            'article_body' => ['required', 'string', 'min:20', 'max:120000'],
            'source_references' => ['nullable', 'string', 'max:15000'],
            'revision_note' => ['nullable', 'string', 'max:1000'],
            'is_commentable' => ['nullable', 'boolean'],
            'is_shareable' => ['nullable', 'boolean'],
        ];
    }
}
