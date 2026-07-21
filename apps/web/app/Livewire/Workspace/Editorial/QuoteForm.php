<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Enums\ReviewStatus;
use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteForm extends Component
{
    public ?string $quote = null;

    /** @var array<string, mixed> */
    public array $state = [
        'english_text' => '',
        'attribution_name' => '',
        'source_title' => '',
        'source_url' => '',
        'language_original_id' => 3049,
        'type_quote_category' => [],
        'is_display_enabled' => true,
        'display_start_date' => null,
        'display_end_date' => null,
        'status_review' => 'P',
        'level_nsfw' => 'N',
        'status_record_lifecycle' => 'ACT',
    ];

    public function mount(?string $quote = null): void
    {
        $this->quote = $quote;

        if ($quote !== null) {
            $record = Quote::query()->findOrFail($quote);
            $this->state = [
                'english_text' => $record->english_text ?? '',
                'attribution_name' => $record->attribution_name ?? '',
                'source_title' => $record->source_title ?? '',
                'source_url' => $record->source_url ?? '',
                'language_original_id' => $record->language_original_id ?? 3049,
                'type_quote_category' => $record->type_quote_category ?? [],
                'is_display_enabled' => (bool) ($record->is_display_enabled ?? true),
                'display_start_date' => $record->display_start_date?->format('Y-m-d'),
                'display_end_date' => $record->display_end_date?->format('Y-m-d'),
                'status_review' => $record->status_review?->value ?? 'P',
                'level_nsfw' => $record->level_nsfw?->value ?? 'N',
                'status_record_lifecycle' => $record->status_record_lifecycle?->value ?? 'ACT',
            ];
        }
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.english_text' => ['required', 'string', 'max:500'],
            'state.attribution_name' => ['nullable', 'string', 'max:150'],
            'state.source_title' => ['nullable', 'string', 'max:200'],
            'state.source_url' => ['nullable', 'url', 'max:500'],
            'state.language_original_id' => ['required', 'integer'],
            'state.type_quote_category' => ['array'],
            'state.is_display_enabled' => ['boolean'],
            'state.display_start_date' => ['nullable', 'date'],
            'state.display_end_date' => ['nullable', 'date', 'after_or_equal:state.display_start_date'],
            'state.status_review' => ['required', 'string'],
            'state.level_nsfw' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $record = $this->quote !== null
            ? Quote::query()->findOrFail($this->quote)
            : new Quote;

        $record->english_text = $this->state['english_text'];
        $record->fill([
            'attribution_name' => $this->state['attribution_name'] ?: null,
            'source_title' => $this->state['source_title'] ?: null,
            'source_url' => $this->state['source_url'] ?: null,
            'language_original_id' => (int) $this->state['language_original_id'],
            'type_quote_category' => array_values($this->state['type_quote_category']),
            'is_display_enabled' => (bool) $this->state['is_display_enabled'],
            'display_start_date' => $this->state['display_start_date'] ?: null,
            'display_end_date' => $this->state['display_end_date'] ?: null,
            'status_review' => $this->state['status_review'],
            'level_nsfw' => $this->state['level_nsfw'],
            'status_record_lifecycle' => $this->state['status_record_lifecycle'],
        ]);
        $record->save();

        session()->flash('editorial_status', $this->quote !== null ? __('editorial.updated') : __('editorial.created'));
        $this->redirectRoute('workspace.editorial.quote.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.workspace.editorial.quote-form', [
            'categoryOptions' => collect(QuoteCategory::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
            'reviewOptions' => collect(ReviewStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
            'nsfwOptions' => collect(NsfwLevel::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
            'lifecycleOptions' => collect(RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
        ])->title(__('editorial.quotes'));
    }
}
