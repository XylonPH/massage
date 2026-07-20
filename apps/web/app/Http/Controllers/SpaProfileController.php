<?php

namespace App\Http\Controllers;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Support\Demo\SampleContent;
use App\Support\Directory\PublicContactLink;
use App\Support\Review\ReviewTarget;
use App\Support\Review\ReviewView;
use Illuminate\View\View;

class SpaProfileController extends Controller
{
    public function __construct(
        private readonly ReviewView $reviewPresenter,
        private readonly PublicContactLink $contactPresenter,
    ) {}

    public function show(string $establishment_slug): View
    {
        $spa = SampleContent::spaProfile($establishment_slug);

        abort_if($spa === null, 404);

        $spa['map_url'] = $this->mapUrl($spa);
        $spa['direction_note'] = $this->localizedText($spa['direction_note'] ?? null);
        $spa['parking_note'] = $this->localizedText($spa['parking_note'] ?? null);
        $spa['contact_channel_list'] = $this->contactPresenter->present($spa['contact_channel_list'] ?? []);

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

    /** @param array<string, mixed> $spa */
    private function mapUrl(array $spa): string
    {
        $latitude = (float) ($spa['coordinate_latitude'] ?? 0);
        $longitude = (float) ($spa['coordinate_longitude'] ?? 0);

        if ($latitude >= -90 && $latitude <= 90 && $longitude >= -180 && $longitude <= 180
            && ($latitude !== 0.0 || $longitude !== 0.0)) {
            return 'https://www.google.com/maps/search/?api=1&query='.
                rawurlencode($latitude.','.$longitude);
        }

        return 'https://www.google.com/maps/search/?api=1&query='.
            rawurlencode($spa['name'].' '.($spa['address_public'] ?? ''));
    }

    private function localizedText(mixed $value): string
    {
        if (! is_array($value)) {
            return trim((string) $value);
        }

        return trim((string) ($value[app()->getLocale()] ?? $value['eng'] ?? reset($value) ?: ''));
    }
}
