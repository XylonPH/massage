<?php

namespace App\Http\Requests\Article;

use App\Enums\ArticleAudience;
use App\Enums\ArticleCategory;
use App\Enums\NsfwLevel;
use App\Models\Article\Article;
use App\Models\User;
use App\Support\Article\ArticleContent;
use App\Support\Article\ArticleLanguage;
use App\Support\Workspace\WorkspaceAccess;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveArticleRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $sources = array_values(array_filter(
            is_array($this->input('source_reference_list')) ? $this->input('source_reference_list') : [],
            static fn (mixed $source): bool => is_array($source) && collect($source)->contains(
                static fn (mixed $value): bool => trim((string) $value) !== '',
            ),
        ));
        $credits = array_values(array_filter(
            is_array($this->input('author_credit_list')) ? $this->input('author_credit_list') : [],
            static fn (mixed $credit): bool => is_array($credit)
                && (trim((string) ($credit['display_name'] ?? '')) !== '' || filled($credit['user_id'] ?? null)),
        ));

        $this->merge([
            'source_reference_list' => $sources,
            'author_credit_list' => $credits,
        ]);

        foreach ($this->relationshipCollections() as $field => $_collection) {
            $values = is_array($this->input($field)) ? $this->input($field) : [];
            $this->merge([$field => array_values(array_unique(array_filter($values, 'is_string')))]);
        }

        if (is_array($this->input('article_owner_user_id_list'))) {
            $this->merge([
                'article_owner_user_id_list' => array_values(array_unique(array_filter(
                    $this->input('article_owner_user_id_list'),
                    'is_string',
                ))),
            ]);
        }

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
            'language_original_id' => ['required', 'integer', Rule::in(ArticleLanguage::ids())],
            'type_article_category' => ['required', Rule::enum(ArticleCategory::class)],
            'target_audience' => ['required', Rule::enum(ArticleAudience::class)],
            'level_nsfw' => ['required', Rule::enum(NsfwLevel::class)],
            'tags' => ['nullable', 'string', 'max:500'],
            'article_body' => ['required', 'string', 'max:120000'],
            'author_credit_list' => ['required', 'array', 'min:1', 'max:10'],
            'author_credit_list.*.user_id' => ['nullable', 'string', 'size:16'],
            'author_credit_list.*.display_name' => ['required', 'string', 'max:100'],
            'article_owner_user_id_list' => ['nullable', 'array', 'max:20'],
            'article_owner_user_id_list.*' => ['string', 'size:16', 'distinct'],
            'source_reference_list' => ['nullable', 'array', 'max:30'],
            'source_reference_list.*.source_title' => ['required', 'string', 'max:200'],
            'source_reference_list.*.source_organization' => ['nullable', 'string', 'max:200'],
            'source_reference_list.*.source_url' => ['nullable', 'url:http,https', 'max:1000'],
            'source_reference_list.*.publication_identifier' => ['nullable', 'string', 'max:120'],
            'related_article_id_list' => ['nullable', 'array', 'max:20'],
            'related_article_id_list.*' => ['string', 'size:16', 'distinct'],
            'related_organization_id_list' => ['nullable', 'array', 'max:20'],
            'related_organization_id_list.*' => ['string', 'size:16', 'distinct'],
            'related_establishment_id_list' => ['nullable', 'array', 'max:20'],
            'related_establishment_id_list.*' => ['string', 'size:16', 'distinct'],
            'related_practitioner_id_list' => ['nullable', 'array', 'max:20'],
            'related_practitioner_id_list.*' => ['string', 'size:16', 'distinct'],
            'related_service_id_list' => ['nullable', 'array', 'max:20'],
            'related_service_id_list.*' => ['string', 'size:16', 'distinct'],
            'related_product_id_list' => ['nullable', 'array', 'max:20'],
            'related_product_id_list.*' => ['string', 'size:16', 'distinct'],
            'revision_note' => ['nullable', 'string', 'max:1000'],
            'is_commentable' => ['nullable', 'boolean'],
            'is_shareable' => ['nullable', 'boolean'],
            'is_anonymous' => ['nullable', 'boolean'],
            'scheduled_publish_at' => ['nullable', 'date', 'after:now'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($this->has('scheduled_publish_at')
                    && ! app(WorkspaceAccess::class)->can($this->user(), 'article.schedule')) {
                    $validator->errors()->add('scheduled_publish_at', __('article.schedule_not_allowed'));
                }

                $article = $this->route('article');
                if ($article instanceof Article
                    && (int) $article->language_original_id !== (int) $this->input('language_original_id')) {
                    $validator->errors()->add('language_original_id', __('article.language_locked'));
                }

                $creditUserIds = collect($this->input('author_credit_list', []))
                    ->pluck('user_id')->filter()->unique()->values()->all();
                if (! $this->usersExist($creditUserIds)) {
                    $validator->errors()->add('author_credit_list', __('article.author_user_invalid'));
                }

                $ownerIds = collect($this->input('article_owner_user_id_list', []))->filter()->unique()->values()->all();
                if (! $this->activeUsersExist($ownerIds)) {
                    $validator->errors()->add('article_owner_user_id_list', __('article.owner_user_invalid'));
                }

                foreach ($this->relationshipCollections() as $field => $collection) {
                    $ids = collect($this->input($field, []))->filter()->unique()->values()->all();
                    if ($field === 'related_article_id_list' && $article instanceof Article
                        && in_array((string) $article->getKey(), $ids, true)) {
                        $validator->errors()->add($field, __('article.related_self_invalid'));

                        continue;
                    }
                    if (! $this->activeRecordsExist($collection, $ids)) {
                        $validator->errors()->add($field, __('article.related_record_invalid'));
                    }
                }
            },
        ];
    }

    /** @param array<int, string> $ids */
    private function usersExist(array $ids): bool
    {
        if ($ids === []) {
            return true;
        }

        return User::query()->whereIn('_id', $ids)->count() === count($ids);
    }

    /** @param array<int, string> $ids */
    private function activeUsersExist(array $ids): bool
    {
        if ($ids === []) {
            return true;
        }

        return User::query()
            ->whereIn('_id', $ids)
            ->where('status_account', 'ACT')
            ->where('status_membership', 'ACT')
            ->count() === count($ids);
    }

    /** @param array<int, string> $ids */
    private function activeRecordsExist(string $collection, array $ids): bool
    {
        if ($ids === []) {
            return true;
        }

        return DB::connection('mongodb')->table($collection)
            ->whereIn('_id', $ids)
            ->where(function ($query): void {
                $query->where('status_record_lifecycle', 'ACT')->orWhereNull('status_record_lifecycle');
            })
            ->count() === count($ids);
    }

    /** @return array<string, string> */
    private function relationshipCollections(): array
    {
        return [
            'related_article_id_list' => 'article_main',
            'related_organization_id_list' => 'organization_main',
            'related_establishment_id_list' => 'establishment_main',
            'related_practitioner_id_list' => 'practitioner_main',
            'related_service_id_list' => 'service_main',
            'related_product_id_list' => 'product_main',
        ];
    }
}
