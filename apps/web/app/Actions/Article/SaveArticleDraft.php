<?php

namespace App\Actions\Article;

use App\Models\Article\Article;
use App\Models\Article\ArticleBody;
use App\Models\Article\ArticleRevision;
use App\Models\Article\Tag;
use App\Models\User;
use App\Support\Article\ArticleContent;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class SaveArticleDraft
{
    public function __construct(private readonly ArticleContent $content) {}

    /** @param array<string, mixed> $input */
    public function execute(array $input, User $user, ?Article $article = null): Article
    {
        $isNew = ! $article;
        $article ??= new Article;
        $userId = (string) $user->getKey();
        $html = $this->content->sanitize((string) $input['article_body']);
        $metrics = $this->content->metrics($html);

        if ($metrics['word_count'] === 0) {
            throw ValidationException::withMessages([
                'article_body' => __('article.validation_body_visible_text'),
            ]);
        }

        $slug = $this->uniqueSlug(
            (string) ($input['article_slug'] ?: Str::slug((string) $input['article_title'])),
            $article->exists ? (string) $article->getKey() : null,
        );
        $oldTagIds = $article->exists ? ($article->tag_id_list ?? []) : [];
        $tagIds = $this->resolveTags((string) ($input['tags'] ?? ''), $userId);
        $translated = static fn (string $text): array => [
            'eng' => ['text' => $text, 'method_translation' => 'HUM', 'status_review' => 'P'],
        ];

        $article->fill([
            'article_title' => $translated(trim((string) $input['article_title'])),
            'article_slug' => $translated($slug),
            'short_description' => $translated(trim((string) $input['short_description'])),
            'language_original_id' => 3049,
            'type_article_category' => $input['type_article_category'],
            'target_audience' => $input['target_audience'],
            'tag_id_list' => $tagIds,
            'author_user_id_list' => $isNew ? [$userId] : $article->author_user_id_list,
            'reading_duration_visual' => $metrics['visual_seconds'],
            'reading_duration_spoken' => $metrics['spoken_seconds'],
            'source_reference_list' => $this->content->parseSources($input['source_references'] ?? null),
            'is_commentable' => (bool) ($input['is_commentable'] ?? false),
            'is_shareable' => (bool) ($input['is_shareable'] ?? false),
            'is_anonymous' => (bool) ($input['is_anonymous'] ?? false),
            'level_nsfw' => $input['level_nsfw'],
            'updated_by_user_id' => $userId,
        ]);

        if (array_key_exists('scheduled_publish_at', $input)) {
            $article->scheduled_publish_at = filled($input['scheduled_publish_at'])
                ? $input['scheduled_publish_at']
                : null;
        }

        if ($isNew) {
            $article->fill([
                'editor_user_id_list' => [],
                'reviewer_user_id_list' => [],
                'photographer_user_id_list' => [],
                'related_article_id_list' => [],
                'related_organization_id_list' => [],
                'related_establishment_id_list' => [],
                'related_practitioner_id_list' => [],
                'related_service_id_list' => [],
                'related_product_id_list' => [],
                'view_count' => 0,
                'comment_count' => 0,
                'save_count' => 0,
                'share_count' => 0,
                'status_publication' => 'D',
                'status_review' => 'P',
                'visibility_scope' => 'PVT',
                'status_record_lifecycle' => 'ACT',
                'record_note' => [],
                'created_by_user_id' => $userId,
            ]);
        }

        $article->save();

        $body = ArticleBody::query()->firstOrNew([
            'article_id' => (string) $article->getKey(),
            'language_id' => 3049,
        ]);
        $body->fill([
            'article_body' => $html,
            'article_plain_text' => $metrics['plain_text'],
            'word_count' => $metrics['word_count'],
            'reading_duration_visual' => $metrics['visual_seconds'],
            'reading_duration_spoken' => $metrics['spoken_seconds'],
            'translator_user_id_list' => [],
            'source_article_body_id' => null,
            'method_translation' => null,
            'status_review' => 'P',
            'status_record_lifecycle' => 'ACT',
            'updated_by_user_id' => $userId,
        ]);
        if (! $body->exists) {
            $body->created_by_user_id = $userId;
        }
        $body->save();

        $nextRevision = ((int) ArticleRevision::query()
            ->where('article_body_id', (string) $body->getKey())
            ->max('revision_number')) + 1;
        ArticleRevision::query()->create([
            'article_id' => (string) $article->getKey(),
            'article_body_id' => (string) $body->getKey(),
            'language_id' => 3049,
            'revision_number' => $nextRevision,
            'article_body' => $html,
            'word_count' => $metrics['word_count'],
            'reading_duration_visual' => $metrics['visual_seconds'],
            'reading_duration_spoken' => $metrics['spoken_seconds'],
            'revision_note' => trim((string) ($input['revision_note'] ?? '')) ?: ($isNew ? 'Initial draft.' : null),
            'review_note' => null,
            'status_review' => 'P',
            'status_record_lifecycle' => 'ACT',
            'created_at' => now(),
            'created_by_user_id' => $userId,
        ]);

        $this->updateTagCounts($oldTagIds, $tagIds);

        return $article->refresh();
    }

    private function uniqueSlug(string $candidate, ?string $ignoreId): string
    {
        $base = mb_substr($candidate !== '' ? $candidate : 'untitled-article', 0, 100);
        $slug = $base;
        $suffix = 2;

        while (Article::query()
            ->where('article_slug.eng.text', $slug)
            ->when($ignoreId, fn ($query) => $query->where('_id', '!=', $ignoreId))
            ->exists()) {
            $ending = '-'.$suffix++;
            $slug = mb_substr($base, 0, 100 - strlen($ending)).$ending;
        }

        return $slug;
    }

    /** @return array<int, string> */
    private function resolveTags(string $input, string $userId): array
    {
        $labels = array_values(array_unique(array_filter(array_map(
            static fn (string $tag): string => trim(mb_strtolower($tag)),
            explode(',', $input),
        ))));
        $ids = [];

        foreach (array_slice($labels, 0, 12) as $label) {
            $slug = mb_substr(Str::slug($label), 0, 80);
            if ($slug === '') {
                continue;
            }

            $tag = Tag::query()->where('tag_slug.eng.text', $slug)->first();
            if (! $tag) {
                $tag = Tag::query()->create([
                    'tag_title' => ['eng' => ['text' => mb_substr($label, 0, 60), 'method_translation' => 'HUM', 'status_review' => 'A']],
                    'tag_slug' => ['eng' => ['text' => $slug, 'method_translation' => 'HUM', 'status_review' => 'A']],
                    'language_original_id' => 3049,
                    'usage_count' => 0,
                    'status_record_lifecycle' => 'ACT',
                    'created_by_user_id' => $userId,
                    'updated_by_user_id' => $userId,
                ]);
            }
            $ids[] = (string) $tag->getKey();
        }

        return array_values(array_unique($ids));
    }

    /** @param array<int, string> $oldIds @param array<int, string> $newIds */
    private function updateTagCounts(array $oldIds, array $newIds): void
    {
        foreach (array_diff($newIds, $oldIds) as $id) {
            Tag::query()->where('_id', $id)->increment('usage_count');
        }
        foreach (array_diff($oldIds, $newIds) as $id) {
            $tag = Tag::query()->find($id);
            if ($tag && $tag->usage_count > 0) {
                $tag->decrement('usage_count');
            }
        }
    }
}
