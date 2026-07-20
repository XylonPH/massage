<?php

namespace Tests\Feature\Review;

use App\Models\Review\Rating;
use App\Models\Review\Review;
use App\Models\User;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class ReviewWorkflowTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpInteractsWithMongoUsers();
        $this->clearReviews();
    }

    protected function tearDown(): void
    {
        $this->clearReviews();
        $this->tearDownInteractsWithMongoUsers();
        parent::tearDown();
    }

    public function test_guest_cannot_start_or_manage_reviews(): void
    {
        $this->get('/spa/the-resting-leaf/review')->assertRedirect('/login');
        $this->get('/therapist/maya-santos/review')->assertRedirect('/login');
        $this->get('/workspace/review')->assertRedirect('/login');
    }

    public function test_member_can_save_a_spa_review_with_simple_rating(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/spa/the-resting-leaf/review', $this->validPayload([
            'mode_rating' => 'SMP',
            'rating_score' => '8',
        ]));

        $review = Review::query()->firstOrFail();
        $rating = Rating::query()->where('review_id', (string) $review->getKey())->firstOrFail();
        $response->assertRedirect(route('workspace.review.edit', $review));
        $this->assertSame('establishment_main', $review->target_collection);
        $this->assertSame('E7sQ2nW9kD4vH8pM', $review->target_record_id);
        $this->assertSame('NR', $review->status_review);
        $this->assertSame('PRV', $review->visibility_scope);
        $this->assertSame([(string) $user->getKey()], $review->author_user_id_list);
        $this->assertSame('SMP', $rating->mode_rating);
        $this->assertSame('8.00', $rating->rating_score);
        $this->assertSame([], $rating->rating_criterion_list);
    }

    public function test_member_can_save_therapist_criteria_with_observation_states(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/therapist/maya-santos/review', $this->validPayload([
            'mode_rating' => 'CRT',
            'rating_score' => null,
            'rating_criteria' => [
                'TEC' => '8',
                'PRS' => '9',
                'PRO' => '10',
                'COM' => 'NOB',
                'REL' => 'NAP',
            ],
        ]))->assertRedirect();

        $review = Review::query()->firstOrFail();
        $rating = Rating::query()->firstOrFail();
        $this->assertSame('practitioner_main', $review->target_collection);
        $this->assertSame('9.00', $rating->rating_score);
        $this->assertCount(5, $rating->rating_criterion_list);
        $this->assertSame('NOB', $rating->rating_criterion_list[3]['status_rating_observation']);
        $this->assertNull($rating->rating_criterion_list[3]['rating_score']);
        $this->assertSame('NAP', $rating->rating_criterion_list[4]['status_rating_observation']);
    }

    public function test_review_requires_substantive_first_hand_text(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/spa/the-resting-leaf/review', $this->validPayload([
            'review_body' => 'Clean place and a good massage.',
        ]))->assertSessionHasErrors('review_body');

        $this->assertSame(0, Review::query()->count());
        $this->assertSame(0, Rating::query()->count());
    }

    public function test_criteria_are_target_specific_and_require_three_scores(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->post('/spa/the-resting-leaf/review', $this->validPayload([
            'mode_rating' => 'CRT',
            'rating_score' => null,
            'rating_criteria' => ['TEC' => '9', 'CLN' => '8', 'CFT' => 'NOB'],
        ]))->assertSessionHasErrors('rating_criteria');

        $this->assertSame(0, Review::query()->count());
    }

    public function test_only_owner_can_edit_and_submitted_review_is_locked(): void
    {
        $author = User::factory()->create();
        $other = User::factory()->create();
        $this->actingAs($author)->post('/therapist/maya-santos/review', $this->validPayload());
        $review = Review::query()->firstOrFail();

        $this->actingAs($other)->get('/workspace/review/'.$review->getKey().'/edit')->assertForbidden();
        $this->actingAs($other)->put('/workspace/review/'.$review->getKey(), $this->validPayload())->assertForbidden();

        $this->actingAs($author)->post('/workspace/review/'.$review->getKey().'/submit')
            ->assertRedirect(route('workspace.review.submitted'));
        $review->refresh();
        $this->assertSame('PND', $review->status_review);
        $this->assertNotNull($review->submitted_at);
        $this->actingAs($author)->get('/workspace/review/'.$review->getKey().'/edit')->assertStatus(409);
    }

    public function test_only_approved_review_is_public_and_anonymous_byline_is_protected(): void
    {
        $user = User::factory()->create(['username' => 'private-reviewer']);
        $this->actingAs($user)->post('/therapist/maya-santos/review', $this->validPayload([
            'review_title' => 'Careful communication throughout the session',
            'is_anonymous' => '1',
            'rating_score' => '9',
        ]));
        $review = Review::query()->firstOrFail();
        $rating = Rating::query()->firstOrFail();

        $this->get('/review/'.$review->review_slug)->assertNotFound();

        $review->forceFill([
            'status_publication' => 'P',
            'status_review' => 'APR',
            'visibility_scope' => 'PUB',
            'status_record_lifecycle' => 'ACT',
            'published_at' => now(),
            'published_by_user_id' => (string) $user->getKey(),
        ])->save();
        $rating->forceFill(['status_rating' => 'ACT'])->save();
        auth()->logout();

        $this->get('/review/'.$review->review_slug)
            ->assertOk()
            ->assertSee('Careful communication throughout the session')
            ->assertSee(__('review.anonymous_reviewer'))
            ->assertDontSee('private-reviewer')
            ->assertSee('9.0 / 10');

        $this->get('/therapist/maya-santos')
            ->assertOk()
            ->assertSee('Careful communication throughout the session')
            ->assertSee(trans_choice('therapist.rating_count', 1, ['count' => 1]))
            ->assertSee(trans_choice('therapist.review_count', 1, ['count' => 1]));
    }

    /** @param array<string, mixed> $overrides @return array<string, mixed> */
    private function validPayload(array $overrides = []): array
    {
        return array_merge([
            'review_title' => 'A calm and professional massage experience',
            'review_body' => implode(' ', array_fill(0, 155, 'helpful')),
            'language_original_id' => '3049',
            'mode_rating' => 'SMP',
            'rating_score' => '8',
            'rating_criteria' => [],
            'date_experience' => now()->subDay()->toDateString(),
            'service_received' => '60-minute massage',
            'amount_paid' => '950.00',
            'type_review_disclosure' => 'SFP',
            'is_anonymous' => '0',
            'level_nsfw' => 'N',
        ], $overrides);
    }

    private function clearReviews(): void
    {
        Rating::query()->delete();
        Review::query()->delete();
    }
}
