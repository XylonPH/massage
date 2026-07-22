<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.quotes') }}</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">Curate and manage platform Quote of the Day content across multiple categories and original languages.</p>
        </div>
        <a href="{{ route('workspace.editorial.quote.create') }}" wire:navigate class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">New Quote</a>
    </div>

    @if (session('editorial_status'))
        <p class="mt-4 rounded-lg border border-leaf-200 bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ session('editorial_status') }}</p>
    @endif

    <div class="mt-5 flex flex-wrap gap-3">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search quote text or attribution..." class="min-w-64 flex-1 rounded-xl border border-ink-200 bg-white px-3.5 py-2 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50" />
        
        <select wire:model.live="category" class="rounded-xl border border-ink-200 bg-white px-3.5 py-2 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->value }}">{{ $cat->getLabel() }}</option>
            @endforeach
        </select>

        <select wire:model.live="lifecycle" class="rounded-xl border border-ink-200 bg-white px-3.5 py-2 text-sm text-ink-950 shadow-sm focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50">
            <option value="">All Statuses</option>
            @foreach ($lifecycleStatuses as $st)
                <option value="{{ $st->value }}">{{ $st->getLabel() }}</option>
            @endforeach
        </select>
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-100 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:text-ink-400">
                    <th class="px-5 py-3.5">Quote</th>
                    <th class="px-4 py-3.5">Category</th>
                    <th class="px-4 py-3.5">Language</th>
                    <th class="px-4 py-3.5">Status</th>
                    <th class="px-4 py-3.5">Published</th>
                    <th class="px-5 py-3.5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($quotes as $q)
                    <tr class="border-b border-ink-100 last:border-0 dark:border-ink-800" wire:key="{{ $q->getKey() }}">
                        <td class="max-w-md px-5 py-3.5">
                            <p class="font-semibold text-ink-950 dark:text-ink-50 line-clamp-2">“{{ $q->original_text }}”</p>
                            @if (!empty($q->attribution_label))
                                <p class="mt-0.5 text-xs text-ink-500 dark:text-ink-400">— {{ $q->attribution_label }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            @if ($q->category_enum)
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-bold {{ $q->category_enum->badgeClass() }}">
                                    {{ $q->category_enum->getLabel() }}
                                </span>
                            @else
                                <span class="text-xs text-ink-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="inline-flex rounded-md bg-ink-100 px-2 py-0.5 text-xs font-bold uppercase tracking-wide text-ink-700 dark:bg-ink-800 dark:text-ink-300">
                                {{ $q->original_language_key }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5">
                            <span class="rounded-full px-2.5 py-0.5 text-xs font-bold {{ $q->status_record_lifecycle?->value === 'ACT' ? 'bg-leaf-100 text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300' : 'bg-ink-100 text-ink-600 dark:bg-ink-800 dark:text-ink-400' }}">
                                {{ $q->status_record_lifecycle?->getLabel() ?? '—' }}
                            </span>
                        </td>
                        <td class="px-4 py-3.5 text-xs text-ink-600 dark:text-ink-300">
                            {{ $q->published_at?->format('M j, Y') ?? 'Immediate' }}
                        </td>
                        <td class="px-5 py-3.5 text-right whitespace-nowrap">
                            <a href="{{ route('workspace.editorial.quote.edit', $q->getKey()) }}" wire:navigate class="text-xs font-bold text-ember-600 hover:underline dark:text-ember-400">Edit</a>
                            @if ($q->status_record_lifecycle?->value === 'ACT')
                                <button type="button" wire:click="retireRecord('{{ $q->getKey() }}')" class="ml-3 text-xs font-semibold text-ink-500 hover:text-amber-600 dark:text-ink-400">Retire</button>
                            @else
                                <button type="button" wire:click="restoreRecord('{{ $q->getKey() }}')" class="ml-3 text-xs font-semibold text-leaf-600 hover:underline dark:text-leaf-400">Restore</button>
                            @endif
                            <button type="button" wire:click="deleteRecord('{{ $q->getKey() }}')" wire:confirm="Are you sure you want to permanently delete this quote?" class="ml-3 text-xs font-semibold text-red-600 hover:underline dark:text-red-400">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center">
                            <p class="font-bold text-ink-700 dark:text-ink-200">No quotes found</p>
                            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">Create a quote or adjust search filters.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $quotes->links() }}</div>
</div>
