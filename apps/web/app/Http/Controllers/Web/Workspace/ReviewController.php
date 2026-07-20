<?php

namespace App\Http\Controllers\Web\Workspace;

use App\Actions\Review\SaveReviewDraft;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\SaveReviewRequest;
use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Support\Review\ReviewTarget;
use App\Support\Review\ReviewView;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewTarget $targets,
        private readonly ReviewView $presenter,
        private readonly SaveReviewDraft $saveDraft,
    ) {}

    public function index(Request $request): View
    {
        return $this->listing($request, 'all');
    }

    public function drafts(Request $request): View
    {
        return $this->listing($request, 'draft');
    }

    public function submitted(Request $request): View
    {
        return $this->listing($request, 'submitted');
    }

    public function published(Request $request): View
    {
        return $this->listing($request, 'published');
    }

    public function createSpa(string $establishment_slug): View
    {
        return $this->createFor($this->targets->spa($establishment_slug));
    }

    public function createTherapist(string $therapist_slug): View
    {
        return $this->createFor($this->targets->therapist($therapist_slug));
    }

    public function storeSpa(SaveReviewRequest $request, string $establishment_slug): RedirectResponse
    {
        return $this->storeFor($request, $this->targets->spa($establishment_slug));
    }

    public function storeTherapist(SaveReviewRequest $request, string $therapist_slug): RedirectResponse
    {
        return $this->storeFor($request, $this->targets->therapist($therapist_slug));
    }

    public function edit(Request $request, Review $review): View
    {
        $this->authorizeOwner($request, $review);
        abort_unless($review->isEditableDraft(), 409, __('review.submitted_locked'));
        $target = $this->targets->byReference($review->target_collection, $review->target_record_id);
        abort_if($target === null, 404);

        return view('workspace.review.form', $this->formData($target, $review));
    }

    public function update(SaveReviewRequest $request, Review $review): RedirectResponse
    {
        $this->authorizeOwner($request, $review);
        abort_unless($review->isEditableDraft(), 409, __('review.submitted_locked'));
        $target = $this->targets->byReference($review->target_collection, $review->target_record_id);
        abort_if($target === null, 404);
        $this->saveDraft->execute($request->validated(), $target, $request->user(), $review);

        return back()->with('status', __('review.draft_updated'));
    }

    public function submit(Request $request, Review $review): RedirectResponse
    {
        $this->authorizeOwner($request, $review);
        abort_unless($review->isEditableDraft(), 409, __('review.submitted_locked'));

        $review->forceFill([
            'status_review' => 'PND',
            'status_record_lifecycle' => 'ACT',
            'submitted_at' => now(),
            'submitted_by_user_id' => (string) $request->user()->getKey(),
            'updated_by_user_id' => (string) $request->user()->getKey(),
        ])->save();

        return redirect()->route('workspace.review.submitted')->with('status', __('review.submitted_for_review'));
    }

    private function listing(Request $request, string $filter): View
    {
        $userId = (string) $request->user()->getKey();
        $query = Review::query()->where('author_user_id_list', $userId);

        match ($filter) {
            'draft' => $query->where('status_review', 'NR')->where('status_publication', 'D'),
            'submitted' => $query->where('status_review', 'PND'),
            'published' => $query->where('status_publication', 'P')->where('status_review', 'APR'),
            default => null,
        };

        $reviews = $query->orderByDesc('updated_at')->paginate(15)->withQueryString();
        $reviews->setCollection($this->presenter->present($reviews->getCollection()));

        return view('workspace.review.index', [
            'filter' => $filter,
            'reviews' => $reviews,
        ]);
    }

    /** @param array<string, mixed>|null $target */
    private function createFor(?array $target): View
    {
        abort_if($target === null, 404);

        return view('workspace.review.form', $this->formData($target));
    }

    /** @param array<string, mixed>|null $target */
    private function storeFor(SaveReviewRequest $request, ?array $target): RedirectResponse
    {
        abort_if($target === null, 404);
        $review = $this->saveDraft->execute($request->validated(), $target, $request->user());

        return redirect()->route('workspace.review.edit', $review)->with('status', __('review.draft_saved'));
    }

    /** @param array<string, mixed> $target @return array<string, mixed> */
    private function formData(array $target, ?Review $review = null): array
    {
        $rating = $review ? Rating::query()->where('review_id', (string) $review->getKey())->first() : null;
        $criteriaValues = collect($rating?->rating_criterion_list ?? [])->mapWithKeys(
            fn (array $item): array => [
                $item['type_rating_criterion'] => ($item['status_rating_observation'] ?? 'SCR') === 'SCR'
                    ? $item['rating_score']
                    : $item['status_rating_observation'],
            ],
        )->all();

        return compact('target', 'review', 'rating', 'criteriaValues');
    }

    private function authorizeOwner(Request $request, Review $review): void
    {
        abort_unless($review->isOwnedBy((string) $request->user()->getKey()), 403);
    }
}
