<div class="mx-auto max-w-5xl">
    <div class="flex items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $quoteId ? 'Edit Quote' : 'New Quote' }}</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Curate quotation, attribution, classification, translations, and publication details.</p>
        </div>
        <a href="{{ route('workspace.editorial.quote.index') }}" wire:navigate class="rounded-lg border border-ink-200 bg-white px-4 py-2 text-sm font-semibold text-ink-700 shadow-sm transition hover:bg-ink-50 dark:border-ink-700 dark:bg-ink-900 dark:text-ink-200">Back to Quotes</a>
    </div>

    @if ($duplicateWarning)
        <div class="mt-4 rounded-xl border border-amber-300 bg-amber-50 p-4 text-sm text-amber-900 dark:border-amber-700 dark:bg-amber-950 dark:text-amber-200">
            <span class="font-bold">Duplicate Notice:</span> {{ $duplicateWarning }}
        </div>
    @endif

    <form wire:submit="save" class="mt-6 grid gap-8 lg:grid-cols-[1fr_20rem]">
        <div class="space-y-6">
            {{-- Classification & Language --}}
            <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <h2 class="text-base font-bold text-ink-950 dark:text-ink-50">1. Classification & Original Language</h2>
                <div class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">Original Language</label>
                        <select wire:model.live="state.language_original_id" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                            @foreach ($languages as $id => $label)
                                <option value="{{ $id }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">Quote Category</label>
                        <select wire:model.live="state.type_quote_category" class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                            @foreach ($categoryOptions as $cat)
                                <option value="{{ $cat->value }}">{{ $cat->getLabel() }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Quote Text & Translations --}}
            <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900" x-data="{ activeTab: '{{ $this->getOriginalLangKeyProperty() }}' }">
                <div class="flex items-center justify-between gap-2 border-b border-ink-100 pb-3 dark:border-ink-800">
                    <h2 class="text-base font-bold text-ink-950 dark:text-ink-50">2. Quote Text & Translations</h2>
                    <span class="text-xs font-semibold text-ink-500">Original: <strong class="uppercase text-ember-600 dark:text-ember-400">{{ $this->getOriginalLangKeyProperty() }}</strong></span>
                </div>

                {{-- Tabs --}}
                <div class="mt-4 flex flex-wrap gap-2 border-b border-ink-100 pb-3 dark:border-ink-800">
                    @foreach (array_keys($translations) as $langKey)
                        <button type="button" @click="activeTab = '{{ $langKey }}'"
                                :class="activeTab === '{{ $langKey }}' ? 'bg-ember-500 text-white font-bold' : 'bg-ink-100 text-ink-700 dark:bg-ink-800 dark:text-ink-300 hover:bg-ink-200'"
                                class="rounded-lg px-3 py-1.5 text-xs font-semibold transition">
                            {{ strtoupper($langKey) }}
                            @if ($langKey === $this->getOriginalLangKeyProperty())
                                <span class="ml-1 rounded bg-black/20 px-1 py-0.2 text-[10px] uppercase">Orig</span>
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- Tab Contents --}}
                @foreach ($translations as $langKey => $transData)
                    <div x-show="activeTab === '{{ $langKey }}'" class="mt-4 space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">
                                Quote Text ({{ strtoupper($langKey) }})
                                @if ($langKey === $this->getOriginalLangKeyProperty())
                                    <span class="text-red-500">* Required Original</span>
                                @endif
                            </label>
                            <textarea wire:model.live="translations.{{ $langKey }}.text" rows="3" maxlength="500" placeholder="Enter quote text in {{ strtoupper($langKey) }}..."
                                      class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white p-3 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50"></textarea>
                            @error("translations.{$langKey}.text")
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-ink-600 dark:text-ink-400">Translation Method</label>
                                <select wire:model="translations.{{ $langKey }}.method_translation" class="mt-1 w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-xs text-ink-950 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                                    <option value="HUM">Human Original / Translation (HUM)</option>
                                    <option value="AI">AI Draft / Assisted (AI)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-ink-600 dark:text-ink-400">Review Status</label>
                                <select wire:model="translations.{{ $langKey }}.status_review" class="mt-1 w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-xs text-ink-950 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                                    <option value="A">Approved (A)</option>
                                    <option value="P">Pending Review (P)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Attribution & Sourcing --}}
            <div class="rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <h2 class="text-base font-bold text-ink-950 dark:text-ink-50">3. Attribution & Verification</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">Attribution Label</label>
                        <input type="text" wire:model.live="state.attribution_label" maxlength="150" placeholder="e.g. José Rizal, Korean proverb, Psalm 46:10, Anonymous"
                               class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50" />
                        <p class="mt-1 text-xs text-ink-500">Public display attribution. Leave empty if completely unattributed.</p>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">Source Title (Optional)</label>
                            <input type="text" wire:model="state.source_title" maxlength="200" placeholder="Book, speech, interview title"
                                   class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-ink-700 dark:text-ink-300">Source Verification URL (Optional)</label>
                            <input type="url" wire:model="state.source_url" maxlength="500" placeholder="https://..."
                                   class="mt-1.5 w-full rounded-xl border border-ink-200 bg-white px-3.5 py-2.5 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Sidebar Settings & Live Preview --}}
        <div class="space-y-6">
            {{-- Preview Panel --}}
            <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900">
                <h3 class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">Live Preview</h3>
                <div class="mt-3">
                    <x-quote-panel :quote="$previewQuote" />
                </div>
            </div>

            {{-- Lifecycle & Publication --}}
            <div class="rounded-2xl border border-ink-100 bg-white p-5 shadow-sm dark:border-ink-800 dark:bg-ink-900 space-y-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-ink-500 dark:text-ink-400">Publication Controls</h3>

                <div>
                    <label class="block text-xs font-bold text-ink-700 dark:text-ink-300">Lifecycle Status</label>
                    <select wire:model="state.status_record_lifecycle" class="mt-1 w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-xs text-ink-950 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                        @foreach ($lifecycleOptions as $st)
                            <option value="{{ $st->value }}">{{ $st->getLabel() }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink-700 dark:text-ink-300">Visibility Scope</label>
                    <select wire:model="state.visibility_scope" class="mt-1 w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-xs text-ink-950 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50">
                        <option value="PUB">Public (PUB)</option>
                        <option value="PRV">Private / Suppressed (PRV)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-ink-700 dark:text-ink-300">Eligible Publication Date</label>
                    <input type="datetime-local" wire:model="state.published_at" class="mt-1 w-full rounded-xl border border-ink-200 bg-white px-3 py-2 text-xs text-ink-950 dark:border-ink-700 dark:bg-ink-950 dark:text-ink-50" />
                    <p class="mt-1 text-[11px] text-ink-500">Future date delays rotation eligibility.</p>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full rounded-xl bg-ember-500 py-3 text-sm font-bold text-white shadow-md transition hover:bg-ember-600 focus:outline-none">
                        {{ $quoteId ? 'Save Changes' : 'Create Quote' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
