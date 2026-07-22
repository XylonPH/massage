@props(['options' => [], 'model', 'live' => false, 'icons' => []])
<div class="flex flex-wrap gap-2" {{ $attributes }}>
    @foreach ($options as $value => $label)
        <label class="cursor-pointer">
            <input type="checkbox" value="{{ $value }}" wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="peer sr-only">
            <span class="inline-flex items-center gap-1 rounded-full border border-ink-200 px-3 py-1.5 text-xs font-semibold text-ink-700 transition peer-checked:border-ember-500 peer-checked:bg-ember-50 peer-checked:text-ember-700 dark:border-ink-700 dark:text-ink-200 dark:peer-checked:bg-ember-950 dark:peer-checked:text-ember-400">
                @if (isset($icons[$value]))
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-3.5 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$value] }}"/></svg>
                @endif
                {{ $label }}
            </span>
        </label>
    @endforeach
</div>
