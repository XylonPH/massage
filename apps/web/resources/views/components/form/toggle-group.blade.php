@props(['options' => [], 'model', 'live' => false, 'icons' => []])
<div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4" {{ $attributes }}>
    @foreach ($options as $value => $label)
        <label class="group relative cursor-pointer">
            <input type="checkbox" value="{{ $value }}" wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="peer sr-only">
            <div class="flex flex-col items-center justify-center gap-2 rounded-2xl border-2 border-slate-200/80 bg-white p-3.5 text-center transition duration-150 peer-checked:border-ember-500 peer-checked:bg-ember-50/60 peer-checked:text-ember-700 peer-checked:shadow-2xs dark:border-ink-700/80 dark:bg-ink-950 dark:peer-checked:border-ember-500 dark:peer-checked:bg-ember-950/60 dark:peer-checked:text-ember-300 hover:border-slate-300 dark:hover:border-ink-600">
                @if (isset($icons[$value]))
                    <div class="inline-flex size-9 items-center justify-center rounded-xl bg-slate-100 text-ink-600 transition group-hover:bg-slate-200/80 peer-checked:bg-ember-100 peer-checked:text-ember-600 dark:bg-ink-900 dark:text-ink-300 dark:group-hover:bg-ink-800 dark:peer-checked:bg-ember-950 dark:peer-checked:text-ember-400">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" class="size-5 shrink-0" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icons[$value] }}"/></svg>
                    </div>
                @endif
                <span class="text-xs font-bold leading-tight text-ink-900 dark:text-ink-100">
                    {{ $label }}
                </span>
            </div>
        </label>
    @endforeach
</div>
