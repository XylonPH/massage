@props(['tabs' => [], 'icons' => [], 'vertical' => false])

@if ($vertical)
    <div class="flex overflow-x-auto gap-1.5 pb-2 sm:pb-0 sm:flex-col sm:overflow-visible sm:-mr-px sm:z-10 sm:space-y-1.5" role="tablist">
        @foreach ($tabs as $key => $label)
            <button type="button" role="tab" wire:key="tab-btn-{{ $key }}" @click="tab = '{{ $key }}'"
                    :aria-selected="(tab === '{{ $key }}').toString()"
                    title="{{ $label }}"
                    :class="tab === '{{ $key }}'
                        ? 'bg-white text-ember-600 dark:bg-ink-900 dark:text-ember-400 font-black border border-slate-200 sm:border-r-0 border-b-2 sm:border-b border-l-4 border-l-ember-500 shadow-sm dark:border-ink-700 dark:border-l-ember-500'
                        : 'border border-transparent bg-slate-200/60 text-ink-600 hover:bg-slate-300/60 hover:text-ink-950 dark:bg-charcoal-900/60 dark:text-ink-300 dark:hover:bg-charcoal-800 dark:hover:text-white font-semibold'"
                    class="flex items-center gap-3 rounded-xl sm:rounded-r-none sm:rounded-l-2xl px-3.5 py-3 text-sm transition-all duration-150 text-left shrink-0 sm:shrink">
                @if (isset($icons[$key]))
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" 
                         :class="tab === '{{ $key }}' ? 'text-ember-500 dark:text-ember-400' : 'text-ink-500 dark:text-ink-400'"
                         class="size-5 shrink-0 transition-colors" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$key] }}"/>
                    </svg>
                @endif
                <span x-show="!collapsed" class="truncate">{{ $label }}</span>
            </button>
        @endforeach
    </div>
@else
    <div class="flex flex-wrap gap-1 border-b border-ink-100 dark:border-ink-800" role="tablist">
        @foreach ($tabs as $key => $label)
            <button type="button" role="tab" wire:key="tab-btn-{{ $key }}" @click="tab = '{{ $key }}'"
                    :aria-selected="(tab === '{{ $key }}').toString()"
                    :class="tab === '{{ $key }}'
                        ? 'border-ember-500 text-ember-600 dark:text-ember-400'
                        : 'border-transparent text-ink-600 hover:text-ink-950 dark:text-ink-300 dark:hover:text-ink-50'"
                    class="-mb-px flex items-center gap-1.5 border-b-2 px-3.5 py-2.5 text-sm font-semibold transition">
                @if (isset($icons[$key]))
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-4 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$key] }}"/></svg>
                @endif
                <span class="truncate">{{ $label }}</span>
            </button>
        @endforeach
    </div>
@endif
