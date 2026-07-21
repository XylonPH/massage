@props(['tabs' => []])
<div class="flex flex-wrap gap-1 border-b border-ink-100 dark:border-ink-800" role="tablist">
    @foreach ($tabs as $key => $label)
        <button type="button" role="tab" @click="tab = '{{ $key }}'"
                :aria-selected="(tab === '{{ $key }}').toString()"
                :class="tab === '{{ $key }}'
                    ? 'border-ember-500 text-ember-600 dark:text-ember-400'
                    : 'border-transparent text-ink-600 hover:text-ink-950 dark:text-ink-300 dark:hover:text-ink-50'"
                class="-mb-px border-b-2 px-3.5 py-2.5 text-sm font-semibold transition">
            {{ $label }}
        </button>
    @endforeach
</div>
