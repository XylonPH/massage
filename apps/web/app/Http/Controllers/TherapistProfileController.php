<?php

namespace App\Http\Controllers;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Support\Demo\SampleContent;
use App\Support\Review\ReviewTarget;
use App\Support\Review\ReviewView;
use Illuminate\View\View;

class TherapistProfileController extends Controller
{
    public function __construct(private readonly ReviewView $reviewPresenter) {}

    public function show(string $therapist_slug): View
    {
        $therapist = SampleContent::therapistProfile($therapist_slug);

        abort_if($therapist === null, 404);

        $reviewQuery = Review::query()->publiclyVisible()
            ->where('target_collection', ReviewTarget::PRACTITIONER)
            ->where('target_record_id', $therapist['id']);
        $therapist['review_count'] = (clone $reviewQuery)->count();
        $therapist['reviews'] = $this->reviewPresenter->present(
            $reviewQuery->orderByDesc('published_at')->limit(3)->get(),
        );
        $therapist['rating'] = null;
        $therapist['rating_count'] = Rating::query()
            ->where('target_collection', ReviewTarget::PRACTITIONER)
            ->where('target_record_id', $therapist['id'])
            ->where('status_rating', 'ACT')
            ->count();

        return view('therapist.profile', ['therapist' => $therapist]);
    }
}
