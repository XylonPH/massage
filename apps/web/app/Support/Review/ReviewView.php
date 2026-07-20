<?php

namespace App\Support\Review;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Models\User;
use Illuminate\Support\Collection;

class ReviewView
{
    public function __construct(private readonly ReviewTarget $targets) {}

    /** @param Collection<int, Review> $reviews @return Collection<int, array<string, mixed>> */
    public function present(Collection $reviews): Collection
    {
        if ($reviews->isEmpty()) {
            return collect();
        }

        $reviewIds = $reviews->map(fn (Review $review): string => (string) $review->getKey())->all();
        $ratings = Rating::query()->whereIn('review_id', $reviewIds)->get()->keyBy('review_id');
        $userIds = $reviews->flatMap(fn (Review $review): array => $review->author_user_id_list ?? [])->unique()->values()->all();
        $users = User::query()->whereIn('_id', $userIds)->get()->keyBy(fn (User $user): string => (string) $user->getKey());

        return $reviews->map(function (Review $review) use ($ratings, $users): array {
            /** @var Rating|null $rating */
            $rating = $ratings->get((string) $review->getKey());
            $target = $this->targets->byReference($review->target_collection, $review->target_record_id);
            $firstAuthorId = $review->author_user_id_list[0] ?? null;
            /** @var User|null $author */
            $author = $firstAuthorId ? $users->get($firstAuthorId) : null;
            $byline = $review->is_anonymous ? __('review.anonymous_reviewer') : ($author?->username ?? __('review.unknown_reviewer'));
            $criteriaLabels = $this->targets->criteria($review->target_collection);
            $criteria = collect($rating?->rating_criterion_list ?? [])->map(fn (array $item): array => [
                'code' => $item['type_rating_criterion'],
                'label' => $criteriaLabels[$item['type_rating_criterion']] ?? $item['type_rating_criterion'],
                'score' => isset($item['rating_score']) ? (int) $item['rating_score'] : null,
                'observation_status' => $item['status_rating_observation'] ?? 'SCR',
            ])->values()->all();

            return [
                'id' => (string) $review->getKey(),
                'slug' => $review->review_slug,
                'title' => $review->review_title,
                'short_description' => $review->short_description,
                'body' => $review->review_body,
                'byline' => $byline,
                'initials' => $review->is_anonymous ? 'A' : strtoupper(mb_substr($byline, 0, 1)),
                'is_anonymous' => $review->is_anonymous,
                'published_at' => $review->published_at,
                'date_experience' => $review->date_experience,
                'service_received' => $review->service_received,
                'amount_paid' => $review->amount_paid,
                'disclosure' => __('review.disclosure_'.$review->type_review_disclosure),
                'level_nsfw' => $review->level_nsfw,
                'reading_duration_visual' => $review->reading_duration_visual,
                'score' => $rating ? (float) $rating->rating_score : null,
                'mode_rating' => $rating?->mode_rating,
                'criteria' => $criteria,
                'target' => $target,
                'status_publication' => $review->status_publication,
                'status_review' => $review->status_review,
                'updated_at' => $review->updated_at,
            ];
        });
    }
}
