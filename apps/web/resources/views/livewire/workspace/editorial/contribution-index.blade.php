<div class="mx-auto max-w-6xl">
    <div class="flex flex-wrap items-end justify-between gap-3">
        <div>
            <a href="{{ route('workspace.editorial.home') }}" wire:navigate class="text-sm font-bold text-ember-600 hover:underline">&larr; {{ __('editorial.title') }}</a>
            <h1 class="mt-2 text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('editorial.contribution_review_title') }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <span class="rounded-full bg-leaf-100 px-3 py-1 text-xs font-bold text-leaf-800 dark:bg-leaf-950 dark:text-leaf-300">
                {{ $contributions->total() }} {{ __('editorial.title') }}
            </span>
        </div>
    </div>

    @if ($contributions->isEmpty())
        <div class="mt-6 rounded-2xl border border-ink-100 bg-white p-10 text-center shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <p class="text-ink-600 dark:text-ink-300">{{ __('editorial.no_pending_contributions') }}</p>
        </div>
    @else
        <div class="mt-6 overflow-x-auto rounded-2xl border border-ink-100 bg-white shadow-sm dark:border-ink-800 dark:bg-ink-900">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-ink-100 bg-ink-50/50 text-xs font-bold uppercase tracking-wider text-ink-500 dark:border-ink-800 dark:bg-ink-950 dark:text-ink-400">
                    <tr>
                        <th class="px-5 py-3">{{ __('editorial.name') }}</th>
                        <th class="px-5 py-3">{{ __('editorial.submitted_at') }}</th>
                        <th class="px-5 py-3 text-right">{{ __('editorial.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-ink-100 dark:divide-ink-800">
                    @foreach ($contributions as $contribution)
                        @php($displayName = data_get($contribution->proposed_data, 'establishment.display_name.eng.text', __('editorial.untitled_contribution')))
                        <tr class="hover:bg-ink-50/50 dark:hover:bg-ink-950/50 transition" wire:key="{{ (string) $contribution->getKey() }}">
                            <td class="px-5 py-4">
                                <p class="font-bold text-ink-950 dark:text-ink-50">{{ $displayName }}</p>
                                <p class="mt-1 text-xs text-ink-500 dark:text-ink-400">{{ (string) $contribution->getKey() }}</p>
                                @if (! empty($contribution->duplicate_candidate_establishment_id_list))
                                    <span class="mt-2 inline-flex items-center gap-1 rounded-md bg-ember-100 px-2.5 py-0.5 text-xs font-bold text-ember-800 dark:bg-ember-950 dark:text-ember-300">
                                        {{ __('editorial.possible_duplicate') }}
                                    </span>
                                @endif
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-xs text-ink-600 dark:text-ink-300">
                                {{ $contribution->submitted_at?->format('M j, Y g:i A') ?? 'N/A' }}
                            </td>

                            <td class="px-5 py-4 text-right whitespace-nowrap">
                                @if (\Illuminate\Support\Facades\Route::has('workspace.editorial.contribution.review'))
                                    <a href="{{ route('workspace.editorial.contribution.review', $contribution) }}" wire:navigate class="font-bold text-amber-600 hover:underline dark:text-amber-400">
                                        {{ __('editorial.review_contribution') }}
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $contributions->links() }}
        </div>
    @endif
</div>
