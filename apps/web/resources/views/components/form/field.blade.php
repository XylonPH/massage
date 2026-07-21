@props(['label', 'name' => null, 'error' => null, 'help' => null])
<div {{ $attributes }}>
    <label @if ($name) for="{{ $name }}" @endif class="mb-1.5 block text-sm font-semibold text-ink-800 dark:text-ink-200">{{ $label }}</label>
    {{ $slot }}
    @if ($help)
        <p class="mt-1.5 text-xs text-ink-500 dark:text-ink-400">{{ $help }}</p>
    @endif
    @if ($error)
        <p class="mt-1.5 text-xs font-semibold text-ember-600 dark:text-ember-400">{{ $error }}</p>
    @endif
</div>
