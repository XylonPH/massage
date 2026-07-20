<?php

namespace App\Http\Requests\Review;

use App\Models\Review\Review;
use App\Support\Review\ReviewContent;
use App\Support\Review\ReviewTarget;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class SaveReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        $review = $this->route('review');

        return ! $review instanceof Review
            || $review->isOwnedBy((string) $this->user()?->getKey());
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'review_title' => ['required', 'string', 'max:75'],
            'review_body' => ['required', 'string', 'max:30000'],
            'language_original_id' => ['required', 'integer', Rule::in([3049, 3600])],
            'mode_rating' => ['required', Rule::in(['SMP', 'CRT'])],
            'rating_score' => ['nullable', 'required_if:mode_rating,SMP', 'integer', 'between:1,10'],
            'rating_criteria' => ['nullable', 'array'],
            'rating_criteria.*' => ['nullable', Rule::in(['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', 'NOB', 'NAP'])],
            'date_experience' => ['nullable', 'date', 'before_or_equal:today'],
            'service_received' => ['nullable', 'string', 'max:160'],
            'amount_paid' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'type_review_disclosure' => ['required', Rule::in(['SFP', 'ANN', 'SPO', 'DSC', 'CMP', 'ORQ', 'PRQ', 'MVT'])],
            'is_anonymous' => ['nullable', 'boolean'],
            'level_nsfw' => ['required', Rule::in(['N', 'M', 'S', 'E'])],
        ];
    }

    public function after(): array
    {
        return [function (Validator $validator): void {
            $metrics = app(ReviewContent::class)->prepare((string) $this->input('review_body'));

            if ($metrics['word_count'] < 150 && $metrics['character_count'] < 900) {
                $validator->errors()->add('review_body', __('review.validation_substance'));
            }

            if ($this->input('mode_rating') !== 'CRT') {
                return;
            }

            $collection = $this->targetCollection();
            $allowed = array_keys(app(ReviewTarget::class)->criteria($collection));
            $answered = array_filter(
                (array) $this->input('rating_criteria', []),
                fn ($value, $code): bool => in_array($code, $allowed, true) && is_numeric($value),
                ARRAY_FILTER_USE_BOTH,
            );

            if (count($answered) < 3) {
                $validator->errors()->add('rating_criteria', __('review.validation_criteria'));
            }

            foreach (array_keys((array) $this->input('rating_criteria', [])) as $code) {
                if (! in_array($code, $allowed, true)) {
                    $validator->errors()->add('rating_criteria', __('review.validation_target_criteria'));
                    break;
                }
            }
        }];
    }

    private function targetCollection(): string
    {
        if ($this->routeIs('spa.review.store')) {
            return ReviewTarget::ESTABLISHMENT;
        }

        if ($this->routeIs('therapist.review.store')) {
            return ReviewTarget::PRACTITIONER;
        }

        $review = $this->route('review');

        return $review instanceof Review ? $review->target_collection : ReviewTarget::PRACTITIONER;
    }
}
