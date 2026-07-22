<?php

namespace Tests\Feature\Editorial;

use App\Livewire\Workspace\Editorial\QuoteForm;
use App\Livewire\Workspace\Editorial\QuoteIndex;
use App\Models\AccessAssignment;
use App\Models\Quote;
use App\Models\User;
use Livewire\Livewire;
use Tests\Concerns\InteractsWithMongoUsers;
use Tests\TestCase;

class QuoteCrudTest extends TestCase
{
    use InteractsWithMongoUsers;

    protected function setUp(): void
    {
        parent::setUp();
        AccessAssignment::query()->delete();
        Quote::query()->delete();
    }

    protected function tearDown(): void
    {
        AccessAssignment::query()->delete();
        Quote::query()->delete();
        parent::tearDown();
    }

    private function editor(): User
    {
        $user = User::factory()->create();
        AccessAssignment::query()->create([
            'user_id' => (string) $user->getKey(),
            'role_workspace' => 'EAD',
            'scope_access' => 'GBL',
            'status_access_assignment' => 'ACT',
            'effective_at' => now()->subMinute(),
        ]);

        return $user;
    }

    public function test_index_lists_and_searches_quotes(): void
    {
        $user = $this->editor();
        Quote::query()->create([
            'quote_text' => ['eng' => ['text' => 'Stillness heals.']],
            'language_original_id' => 3049,
            'type_quote_category' => 'RRL',
            'attribution_label' => 'Anonymous',
        ]);
        Quote::query()->create([
            'quote_text' => ['eng' => ['text' => 'Movement restores.']],
            'language_original_id' => 3049,
            'type_quote_category' => 'RRL',
            'attribution_label' => 'Anonymous',
        ]);

        Livewire::actingAs($user)
            ->test(QuoteIndex::class)
            ->assertSee('Stillness heals.')
            ->assertSee('Movement restores.')
            ->set('search', 'Stillness')
            ->assertSee('Stillness heals.')
            ->assertDontSee('Movement restores.');
    }

    public function test_editor_can_create_a_quote(): void
    {
        $user = $this->editor();

        Livewire::actingAs($user)
            ->test(QuoteForm::class)
            ->set('translations.eng.text', 'Rest is productive.')
            ->set('state.attribution_label', 'Unknown')
            ->call('save')
            ->assertRedirect(route('workspace.editorial.quote.index'));

        $this->assertSame(1, Quote::query()->count());
        $this->assertSame('Rest is productive.', Quote::query()->first()->original_text);
    }

    public function test_create_requires_original_language_text(): void
    {
        Livewire::actingAs($this->editor())
            ->test(QuoteForm::class)
            ->set('translations.eng.text', '')
            ->call('save')
            ->assertHasErrors(['translations.eng.text']);
    }

    public function test_editor_can_update_a_quote(): void
    {
        $user = $this->editor();
        $quote = Quote::query()->create([
            'quote_text' => ['eng' => ['text' => 'Old text.']],
            'language_original_id' => 3049,
            'type_quote_category' => 'RRL',
        ]);

        Livewire::actingAs($user)
            ->test(QuoteForm::class, ['quote' => (string) $quote->getKey()])
            ->assertSet('translations.eng.text', 'Old text.')
            ->set('translations.eng.text', 'New text.')
            ->call('save');

        $this->assertSame('New text.', $quote->refresh()->original_text);
    }

    public function test_editor_can_delete_a_quote(): void
    {
        $user = $this->editor();
        $quote = Quote::query()->create([
            'quote_text' => ['eng' => ['text' => 'Doomed.']],
            'language_original_id' => 3049,
            'type_quote_category' => 'RRL',
        ]);

        Livewire::actingAs($user)
            ->test(QuoteIndex::class)
            ->call('deleteRecord', (string) $quote->getKey());

        $this->assertSame(0, Quote::query()->count());
    }
}
