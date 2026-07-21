<div class="mx-auto max-w-5xl">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.establishments') }}</h1>
            <p class="mt-1 text-sm text-ink-600 dark:text-ink-300">{{ __('editorial.intro') }}</p>
        </div>
        <a href="{{ route('workspace.editorial.establishment.create') }}" wire:navigate class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.new') }}</a>
    </div>

    @if (session('editorial_status'))
        <p class="mt-4 rounded-lg border border-leaf-200 bg-leaf-50 px-4 py-2.5 text-sm font-semibold text-leaf-700 dark:border-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">{{ session('editorial_status') }}</p>
    @endif

    <div class="mt-5">
        <x-form.input wire:model.live.debounce.300ms="search" placeholder="{{ __('editorial.search_placeholder') }}" class="max-w-sm" />
    </div>

    <div class="mt-4 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <table class="w-full text-left text-sm">
            <thead>
                <tr class="border-b border-ink-100 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:text-ink-400">
                    <th class="px-5 py-3">{{ __('editorial.name') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.status') }}</th>
                    <th class="px-5 py-3">{{ __('editorial.updated_at') }}</th>
                    <th class="px-5 py-3 text-right">{{ __('editorial.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($establishments as $establishment)
                    <tr class="border-b border-ink-100 last:border-0 dark:border-ink-800" wire:key="{{ $establishment->getKey() }}">
                        <td class="max-w-md truncate px-5 py-3 font-semibold text-ink-950 dark:text-ink-50">{{ $establishment->english_name }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full bg-ink-50 px-2.5 py-1 text-xs font-bold text-ink-700 dark:bg-ink-800 dark:text-ink-200">{{ $establishment->status_record_lifecycle?->getLabel() ?? '—' }}</span>
                        </td>
                        <td class="px-5 py-3 text-ink-600 dark:text-ink-300">{{ $establishment->updated_at?->format('M j, Y') }}</td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('workspace.editorial.establishment.edit', $establishment->getKey()) }}" wire:navigate class="text-sm font-bold text-ember-600 hover:text-ember-700 dark:text-ember-400 dark:hover:text-ember-300">{{ __('editorial.edit') }}</a>
                            <button type="button" wire:click="deleteRecord('{{ $establishment->getKey() }}')" wire:confirm="{{ __('editorial.delete_confirm') }}" class="ml-3 text-sm font-bold text-ink-500 hover:text-ember-600 dark:text-ink-400 dark:hover:text-ember-400">{{ __('editorial.delete') }}</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-5 py-10 text-center">
                            <p class="font-bold text-ink-700 dark:text-ink-200">{{ __('editorial.empty_title') }}</p>
                            <p class="mt-1 text-sm text-ink-500 dark:text-ink-400">{{ __('editorial.empty_text') }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $establishments->links() }}</div>
</div>
