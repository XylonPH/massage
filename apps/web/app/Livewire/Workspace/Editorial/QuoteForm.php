<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\NsfwLevel;
use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
use App\Models\Quote;
use App\Services\Quote\QuoteRotationService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteForm extends Component
{
    public ?string $quoteId = null;

    /** @var array<string, mixed> */
    public array $state = [
        'language_original_id' => 3049,
        'type_quote_category' => 'WEL',
        'attribution_label' => '',
        'source_title' => '',
        'source_url' => '',
        'visibility_scope' => 'PUB',
        'level_nsfw' => 'N',
        'status_record_lifecycle' => 'ACT',
        'published_at' => '',
    ];

    /** @var array<string, array{text: string, method_translation: string, status_review: string}> */
    public array $translations = [
        'eng' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'fil' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'ceb' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'kor' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'spa' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'zho-hans' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
        'zho-hant' => ['text' => '', 'method_translation' => 'HUM', 'status_review' => 'A'],
    ];

    public ?string $duplicateWarning = null;

    public function mount(?string $quote = null): void
    {
        $this->quoteId = $quote;

        if ($quote !== null) {
            /** @var Quote $record */
            $record = Quote::query()->findOrFail($quote);
            $this->state = [
                'language_original_id' => (int) ($record->language_original_id ?? 3049),
                'type_quote_category' => is_array($record->type_quote_category) ? ($record->type_quote_category[0] ?? 'WEL') : ($record->type_quote_category ?? 'WEL'),
                'attribution_label' => $record->attribution_label ?? '',
                'source_title' => $record->source_title ?? '',
                'source_url' => $record->source_url ?? '',
                'visibility_scope' => $record->visibility_scope ?? 'PUB',
                'level_nsfw' => $record->level_nsfw?->value ?? 'N',
                'status_record_lifecycle' => $record->status_record_lifecycle?->value ?? 'ACT',
                'published_at' => $record->published_at?->format('Y-m-d\TH:i') ?? '',
            ];

            if (is_array($record->quote_text)) {
                foreach ($record->quote_text as $langKey => $val) {
                    if (is_array($val) && isset($val['text'])) {
                        $this->translations[$langKey] = [
                            'text' => $val['text'] ?? '',
                            'method_translation' => $val['method_translation'] ?? 'HUM',
                            'status_review' => $val['status_review'] ?? 'A',
                        ];
                    }
                }
            }
        } else {
            $this->state['published_at'] = now()->format('Y-m-d\TH:i');
        }
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.language_original_id' => ['required', 'integer'],
            'state.type_quote_category' => ['required', 'string'],
            'state.attribution_label' => ['nullable', 'string', 'max:150'],
            'state.source_title' => ['nullable', 'string', 'max:200'],
            'state.source_url' => ['nullable', 'url', 'max:500'],
            'state.visibility_scope' => ['required', 'string'],
            'state.level_nsfw' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
            'state.published_at' => ['nullable', 'string'],
            'translations.*.text' => ['nullable', 'string', 'max:500'],
            'translations.*.method_translation' => ['nullable', 'string'],
            'translations.*.status_review' => ['nullable', 'string'],
        ];
    }

    public function updated($propertyName): void
    {
        $this->checkForDuplicates();
    }

    public function checkForDuplicates(): void
    {
        $this->duplicateWarning = null;
        $origLangKey = $this->getOriginalLangKeyProperty();

        $text = trim($this->translations[$origLangKey]['text'] ?? '');
        if ($text === '') {
            return;
        }

        $attr = trim($this->state['attribution_label'] ?? '');

        $query = Quote::query()
            ->where("quote_text.{$origLangKey}.text", 'like', $text);

        if ($this->quoteId) {
            $query->where('_id', '!=', $this->quoteId);
        }

        if ($attr !== '') {
            $query->where('attribution_label', $attr);
        }

        $match = $query->first();
        if ($match) {
            $this->duplicateWarning = "A quote with matching text in {$origLangKey} already exists (ID: {$match->getKey()}).";
        }
    }

    public function getOriginalLangKeyProperty(): string
    {
        $map = [
            3049 => 'eng',
            3600 => 'fil',
            1458 => 'ceb',
            7142 => 'kor',
            12559 => 'spa',
            17097 => 'zho-hans',
        ];

        return $map[(int) $this->state['language_original_id']] ?? 'eng';
    }

    public function save(QuoteRotationService $rotationService): void
    {
        $this->validate();

        $origKey = $this->getOriginalLangKeyProperty();

        // Ensure original language has text
        if (empty(trim($this->translations[$origKey]['text'] ?? ''))) {
            $this->addError("translations.{$origKey}.text", "Original language ({$origKey}) quote text is required.");

            return;
        }

        $record = $this->quoteId !== null
            ? Quote::query()->findOrFail($this->quoteId)
            : new Quote;

        // Build filtered quote_text payload
        $quoteTextPayload = [];
        foreach ($this->translations as $langKey => $val) {
            if (filled(trim($val['text'] ?? ''))) {
                $quoteTextPayload[$langKey] = [
                    'text' => trim($val['text']),
                    'method_translation' => $val['method_translation'] ?? 'HUM',
                    'status_review' => $val['status_review'] ?? 'A',
                ];
            }
        }

        $record->fill([
            'quote_text' => $quoteTextPayload,
            'language_original_id' => (int) $this->state['language_original_id'],
            'type_quote_category' => $this->state['type_quote_category'],
            'attribution_label' => filled($this->state['attribution_label']) ? trim($this->state['attribution_label']) : null,
            'source_title' => filled($this->state['source_title']) ? trim($this->state['source_title']) : null,
            'source_url' => filled($this->state['source_url']) ? trim($this->state['source_url']) : null,
            'visibility_scope' => $this->state['visibility_scope'],
            'level_nsfw' => $this->state['level_nsfw'],
            'status_record_lifecycle' => $this->state['status_record_lifecycle'],
            'published_at' => filled($this->state['published_at']) ? Carbon::parse($this->state['published_at']) : now(),
        ]);

        $record->save();

        $rotationService->clearCache();

        session()->flash('editorial_status', $this->quoteId !== null ? 'Quote record updated successfully.' : 'Quote record created successfully.');
        $this->redirectRoute('workspace.editorial.quote.index', navigate: true);
    }

    public function render(): View
    {
        $origKey = $this->getOriginalLangKeyProperty();
        $text = $this->translations[$origKey]['text'] ?? '';

        $previewQuote = [
            'category' => QuoteCategory::tryFrom($this->state['type_quote_category']),
            'text' => filled($text) ? $text : 'Your quote text preview will appear here...',
            'attribution_label' => $this->state['attribution_label'] ?: 'Author or Source',
            'source_title' => $this->state['source_title'] ?: null,
            'source_url' => $this->state['source_url'] ?: null,
            'language_key' => $origKey,
            'is_original' => true,
            'original_text' => $text,
            'original_language_key' => $origKey,
        ];

        return view('livewire.workspace.editorial.quote-form', [
            'categoryOptions' => QuoteCategory::cases(),
            'nsfwOptions' => NsfwLevel::cases(),
            'lifecycleOptions' => RecordLifecycleStatus::cases(),
            'languages' => [
                3049 => 'English (eng)',
                3600 => 'Filipino (fil)',
                1458 => 'Cebuano (ceb)',
                7142 => 'Korean (kor)',
                12559 => 'Spanish (spa)',
                17097 => 'Chinese (zho-hans / zho-hant)',
            ],
            'previewQuote' => $previewQuote,
        ])->title($this->quoteId ? 'Edit Quote' : 'New Quote');
    }
}
