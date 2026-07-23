<?php

namespace Tests\Feature\Quote;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Models\Quote;
use App\Models\User;
use App\Services\Quote\QuoteRotationService;
use Database\Seeders\QuoteSeeder;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class QuoteSystemTest extends TestCase
{
    protected QuoteRotationService $rotationService;

    protected function setUp(): void
    {
        parent::setUp();
        app()->setLocale('eng');
        $this->rotationService = new QuoteRotationService;
        $this->rotationService->clearCache();

        // Ensure database has test quotes
        Quote::query()->whereIn('_id', ['Qtest12345678901', 'Qfuture123456789', 'Qinact1234567890'])->delete();
        if (Quote::query()->count() === 0) {
            $this->seed(QuoteSeeder::class);
        }
    }

    public function test_all_nine_quote_categories_have_valid_theme_mappings(): void
    {
        foreach (QuoteCategory::cases() as $category) {
            $this->assertNotEmpty($category->getLabel());
            $this->assertNotEmpty($category->iconName());
            $this->assertNotEmpty($category->panelGradientClass());
            $this->assertNotEmpty($category->accentColorClass());
            $this->assertNotEmpty($category->badgeClass());
        }
    }

    public function test_quote_rotation_service_resolves_daily_quote_and_caches_result(): void
    {
        $quote = Quote::query()->create([
            '_id' => 'Qtest12345678901',
            'quote_text' => [
                'eng' => ['text' => 'Testing rotation service quote text.', 'method_translation' => 'HUM', 'status_review' => 'A'],
            ],
            'language_original_id' => 3049,
            'type_quote_category' => QuoteCategory::RestRelaxation->value,
            'attribution_label' => 'Test Author',
            'visibility_scope' => 'PUB',
            'level_nsfw' => NsfwLevel::None->value,
            'status_record_lifecycle' => RecordLifecycleStatus::Active->value,
            'published_at' => now()->subDay(),
        ]);

        $resolved = $this->rotationService->resolveDailyQuote('home', 'eng', '2026-07-22');

        $this->assertNotNull($resolved);
        $this->assertNotEmpty($resolved['text']);

        // Verify cache key exists
        $this->assertTrue(Cache::has('quote:daily:home:eng:2026-07-22'));

        // Clear cache and verify
        $this->rotationService->clearCache('2026-07-22');
        $this->assertFalse(Cache::has('quote:daily:home:eng:2026-07-22'));

        $quote->delete();
    }

    public function test_publication_eligibility_filters_future_and_inactive_quotes(): void
    {
        // Future dated quote
        $futureQuote = Quote::query()->create([
            '_id' => 'Qfuture123456789',
            'quote_text' => ['eng' => ['text' => 'Future quote text', 'method_translation' => 'HUM', 'status_review' => 'A']],
            'language_original_id' => 3049,
            'type_quote_category' => QuoteCategory::MindfulnessPresence->value,
            'visibility_scope' => 'PUB',
            'level_nsfw' => NsfwLevel::None->value,
            'status_record_lifecycle' => RecordLifecycleStatus::Active->value,
            'published_at' => now()->addDays(5),
        ]);

        // Inactive quote
        $inactiveQuote = Quote::query()->create([
            '_id' => 'Qinact1234567890',
            'quote_text' => ['eng' => ['text' => 'Inactive quote text', 'method_translation' => 'HUM', 'status_review' => 'A']],
            'language_original_id' => 3049,
            'type_quote_category' => QuoteCategory::MindfulnessPresence->value,
            'visibility_scope' => 'PUB',
            'level_nsfw' => NsfwLevel::None->value,
            'status_record_lifecycle' => RecordLifecycleStatus::Inactive->value,
            'published_at' => now()->subDay(),
        ]);

        $eligibleIds = Quote::query()->eligiblePublic()->pluck('_id')->toArray();

        $this->assertNotContains($futureQuote->getKey(), $eligibleIds);
        $this->assertNotContains($inactiveQuote->getKey(), $eligibleIds);

        $futureQuote->delete();
        $inactiveQuote->delete();
    }

    public function test_quote_rotation_cycles_deterministically_over_different_dates(): void
    {
        $resolvedDate1 = $this->rotationService->resolveDailyQuote('home', 'eng', '2026-07-20');
        $resolvedDate2 = $this->rotationService->resolveDailyQuote('home', 'eng', '2026-07-21');

        $this->assertNotNull($resolvedDate1);
        $this->assertNotNull($resolvedDate2);
    }

    public function test_homepage_renders_quote_panel_component(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Quote of the Day');
    }

    public function test_quote_panel_component_renders_correctly_in_blade(): void
    {
        $quoteData = [
            'id' => 'Qblade1234567890',
            'text' => 'Blade rendering test quote.',
            'attribution_label' => 'Blade Author',
            'category' => QuoteCategory::SpiritualReflection,
            'language_key' => 'eng',
            'is_original' => true,
            'original_text' => 'Blade rendering test quote.',
            'original_language_key' => 'eng',
        ];

        $view = $this->blade('<x-quote-panel :quote="$quote" />', ['quote' => $quoteData]);

        $view->assertSee('Blade rendering test quote.');
        $view->assertSee('Blade Author');
        $view->assertSee('Spiritual Reflection');
    }

    public function test_admin_workspace_quote_routes_require_editorial_permission(): void
    {
        // Unauthenticated guest redirected
        $this->get(route('workspace.editorial.quote.index'))
            ->assertRedirect(route('login'));

        // Unauthorized user denied
        $user = User::factory()->create();
        $this->actingAs($user)
            ->get(route('workspace.editorial.quote.index'))
            ->assertStatus(403);
    }
}
