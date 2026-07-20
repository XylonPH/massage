<?php

namespace App\Http\Controllers;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Support\Demo\SampleContent;
use App\Support\Review\ReviewTarget;
use App\Support\Review\ReviewView;
use Illuminate\View\View;

class SpaProfileController extends Controller
{
    public function __construct(private readonly ReviewView $reviewPresenter) {}

    public function show(string $establishment_slug): View
    {
        $spa = SampleContent::spaProfile($establishment_slug);

        abort_if($spa === null, 404);

        $reviewQuery = Review::query()->publiclyVisible()
            ->where('target_collection', ReviewTarget::ESTABLISHMENT)
            ->where('target_record_id', $spa['id']);
        $spa['review_count'] = (clone $reviewQuery)->count();
        $spa['reviews'] = $this->reviewPresenter->present(
            $reviewQuery->orderByDesc('published_at')->limit(3)->get(),
        );
        $spa['rating'] = null;
        $spa['rating_count'] = Rating::query()
            ->where('target_collection', ReviewTarget::ESTABLISHMENT)
            ->where('target_record_id', $spa['id'])
            ->where('status_rating', 'ACT')
            ->count();

        return view('spa.profile', ['spa' => $spa]);
    }
}
