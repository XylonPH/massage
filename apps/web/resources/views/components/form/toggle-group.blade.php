@props(['options' => [], 'model', 'live' => false, 'icons' => []])
<div class="grid grid-cols-2 gap-3.5 sm:grid-cols-3 md:grid-cols-4" {{ $attributes }}>
    @foreach ($options as $value => $label)
        <label class="group relative cursor-pointer">
            <input type="checkbox" value="{{ $value }}" wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="peer sr-only">
            <div class="flex flex-col items-center justify-center gap-2.5 rounded-2xl border-2 border-slate-200/80 bg-white p-4 text-center transition duration-150 peer-checked:border-ember-500 peer-checked:bg-ember-50/70 peer-checked:text-ember-700 peer-checked:shadow-2xs dark:border-ink-700/80 dark:bg-ink-950 dark:peer-checked:border-ember-500 dark:peer-checked:bg-ember-950/70 dark:peer-checked:text-ember-300 hover:border-slate-300 dark:hover:border-ink-600">
                @if (isset($icons[$value]))
                    <div class="inline-flex size-12 items-center justify-center rounded-2xl bg-slate-100 text-ink-600 transition duration-150 group-hover:scale-105 group-hover:bg-slate-200/80 peer-checked:bg-ember-100 peer-checked:text-ember-600 dark:bg-ink-900 dark:text-ink-300 dark:group-hover:bg-ink-800 dark:peer-checked:bg-ember-950 dark:peer-checked:text-ember-400 sm:size-14">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-6 shrink-0 sm:size-7" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$value] }}"/></svg>
                    </div>
                @endif
                <span class="text-xs font-bold leading-tight text-ink-900 dark:text-ink-100">
                    {{ $label }}
                </span>
            </div>
        </label>
    @endforeach
</div>
