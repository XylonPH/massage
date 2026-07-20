<?php

namespace App\Http\Controllers\Web\Public;

use App\Http\Controllers\Controller;
use App\Models\Review\Review;
use App\Support\Review\ReviewTarget;
use App\Support\Review\ReviewView;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private readonly ReviewView $presenter) {}

    public function index(Request $request): View
    {
        return $this->listing($request, Review::query(), __('review.public_title'));
    }

    public function spas(Request $request): View
    {
        $query = Review::query()->where('target_collection', ReviewTarget::ESTABLISHMENT);
        $heading = __('review.spa_reviews');
        if ($request->filled('target')) {
            $target = app(ReviewTarget::class)->spa($request->string('target')->toString());
            abort_if($target === null, 404);
            $query->where('target_record_id', $target['id']);
            $heading = __('review.reviews_of', ['target' => $target['name']]);
        }

        return $this->listing(
            $request,
            $query,
            $heading,
        );
    }

    public function therapists(Request $request): View
    {
        $query = Review::query()->where('target_collection', ReviewTarget::PRACTITIONER);
        $heading = __('review.therapist_reviews');
        if ($request->filled('target')) {
            $target = app(ReviewTarget::class)->therapist($request->string('target')->toString());
            abort_if($target === null, 404);
            $query->where('target_record_id', $target['id']);
            $heading = __('review.reviews_of', ['target' => $target['name']]);
        }

        return $this->listing(
            $request,
            $query,
            $heading,
        );
    }

    public function show(string $review_slug): View
    {
        $review = Review::query()->publiclyVisible()->where('review_slug', $review_slug)->firstOrFail();
        $item = $this->presenter->present(collect([$review]))->firstOrFail();

        return view('review.show', ['item' => $item]);
    }

    private function listing(Request $request, Builder $query, string $heading): View
    {
        $reviews = $query->publiclyVisible()->orderByDesc('published_at')->paginate(12)->withQueryString();
        $reviews->setCollection($this->presenter->present($reviews->getCollection()));

        return view('review.index', [
            'heading' => $heading,
            'reviews' => $reviews,
        ]);
    }
}
