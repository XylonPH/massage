@props(['options' => [], 'model', 'live' => false])
<div class="flex flex-wrap gap-2" {{ $attributes }}>
    @foreach ($options as $value => $label)
        <label class="cursor-pointer">
            <input type="checkbox" value="{{ $value }}" wire:model{{ $live ? '.live' : '' }}="{{ $model }}" class="peer sr-only">
            <span class="inline-block rounded-full border border-ink-200 px-3 py-1.5 text-xs font-semibold text-ink-700 transition peer-checked:border-ember-500 peer-checked:bg-ember-50 peer-checked:text-ember-700 dark:border-ink-700 dark:text-ink-200 dark:peer-checked:bg-ember-950 dark:peer-checked:text-ember-400">{{ $label }}</span>
        </label>
    @endforeach
</div>
