@props(['options' => [], 'placeholder' => null])
<select {{ $attributes->merge(['class' => 'w-full rounded-lg border border-ink-200 bg-white px-3 py-2 text-sm text-ink-950 focus:border-ember-400 focus:outline-none dark:border-ink-700 dark:bg-ink-900 dark:text-ink-50']) }}>
    @if ($placeholder !== null && ! $attributes->has('multiple'))
        <option value="">{{ $placeholder }}</option>
    @endif
    @foreach ($options as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
