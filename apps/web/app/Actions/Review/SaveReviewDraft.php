<?php

namespace App\Actions\Review;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Models\User;
use App\Support\Review\ReviewContent;
use Illuminate\Support\Str;

class SaveReviewDraft
{
    public function __construct(private readonly ReviewContent $content) {}

    /** @param array<string, mixed> $input @param array<string, mixed> $target */
    public function execute(array $input, array $target, User $user, ?Review $review = null): Review
    {
        $isNew = ! $review;
        $review ??= new Review;
        $userId = (string) $user->getKey();
        $prepared = $this->content->prepare((string) $input['review_body']);
        $criteria = $this->criteria($input, $target);
        $scoredCriteria = array_values(array_filter($criteria, fn (array $item): bool => $item['status_rating_observation'] === 'SCR'));
        $score = $input['mode_rating'] === 'CRT'
            ? round(array_sum(array_column($scoredCriteria, 'rating_score')) / count($scoredCriteria), 2)
            : (int) $input['rating_score'];

        $review->fill([
            'review_title' => trim((string) $input['review_title']),
            'review_slug' => $this->uniqueSlug((string) $input['review_title'], $review->exists ? (string) $review->getKey() : null),
            'short_description' => $prepared['short_description'],
            'review_body' => $prepared['body'],
            'language_original_id' => (int) $input['language_original_id'],
            'type_review' => 'USR',
            'target_collection' => $target['collection'],
            'target_record_id' => $target['id'],
            'author_user_id_list' => $isNew ? [$userId] : $review->author_user_id_list,
            'is_anonymous' => (bool) ($input['is_anonymous'] ?? false),
            'date_experience' => $input['date_experience'] ?: null,
            'service_received' => filled($input['service_received'] ?? null) ? trim((string) $input['service_received']) : null,
            'amount_paid' => filled($input['amount_paid'] ?? null) ? round((float) $input['amount_paid'], 2) : null,
            'currency_id' => 111,
            'type_review_disclosure' => $input['type_review_disclosure'],
            'reading_duration_visual' => $prepared['visual_seconds'],
            'reading_duration_spoken' => $prepared['spoken_seconds'],
            'level_nsfw' => $input['level_nsfw'],
            'updated_by_user_id' => $userId,
        ]);

        if ($isNew) {
            $review->fill([
                'status_publication' => 'D',
                'status_review' => 'NR',
                'visibility_scope' => 'PRV',
                'status_record_lifecycle' => 'DRA',
                'record_note' => [],
                'created_by_user_id' => $userId,
            ]);
        }

        $review->save();

        $rating = Rating::query()->firstOrNew(['review_id' => (string) $review->getKey()]);
        $rating->fill([
            'target_collection' => $target['collection'],
            'target_record_id' => $target['id'],
            'review_id' => (string) $review->getKey(),
            'created_by_user_id' => $userId,
            'mode_rating' => $input['mode_rating'],
            'rating_score' => $score,
            'rating_criterion_list' => $criteria,
            'type_rating_source' => 'RVW',
            'date_experience' => $input['date_experience'] ?: null,
            'status_rating' => $rating->exists ? $rating->status_rating : 'DRA',
        ]);
        $rating->save();

        if ($review->rating_id !== (string) $rating->getKey()) {
            $review->forceFill(['rating_id' => (string) $rating->getKey()])->save();
        }

        return $review->refresh();
    }

    /** @param array<string, mixed> $input @param array<string, mixed> $target @return array<int, array{type_rating_criterion: string, status_rating_observation: string, rating_score: int|null}> */
    private function criteria(array $input, array $target): array
    {
        if ($input['mode_rating'] !== 'CRT') {
            return [];
        }

        $criteria = [];
        foreach ($target['criteria'] as $code => $label) {
            $value = $input['rating_criteria'][$code] ?? 'NOB';
            $status = in_array($value, ['NOB', 'NAP'], true) ? $value : 'SCR';
            $criteria[] = [
                'type_rating_criterion' => $code,
                'status_rating_observation' => $status,
                'rating_score' => $status === 'SCR' ? (int) $value : null,
            ];
        }

        return $criteria;
    }

    private function uniqueSlug(string $title, ?string $ignoreId): string
    {
        $base = Str::limit(Str::slug($title), 90, '');
        $base = $base !== '' ? $base : 'review';
        $slug = $base;
        $suffix = 2;

        while (Review::query()->where('review_slug', $slug)
            ->when($ignoreId, fn ($query) => $query->where('_id', '!=', $ignoreId))
            ->exists()) {
            $slug = Str::limit($base, 94, '').'-'.$suffix++;
        }

        return $slug;
    }
}
