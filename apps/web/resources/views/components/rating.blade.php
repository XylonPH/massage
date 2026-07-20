@props(['value' => 0, 'size' => 'size-3.5'])

@php
    $full = (int) floor($value);
    $half = ($value - $full) >= 0.25 && ($value - $full) < 0.75;
    $rounded = $half ? $full : (int) round($value);
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5']) }} role="img" aria-label="{{ __('common.rating_out_of_five', ['rating' => number_format($value, 1)]) }}">
    @for ($i = 1; $i <= 5; $i++)
        @if ($i <= $full || (! $half && $i <= $rounded))
            <svg viewBox="0 0 20 20" fill="currentColor" class="{{ $size }} text-ember-400" aria-hidden="true"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.95a1 1 0 0 0 .95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.44a1 1 0 0 0-.36 1.12l1.28 3.95c.3.92-.75 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.79.57-1.84-.2-1.54-1.12l1.28-3.95a1 1 0 0 0-.36-1.12L2.05 9.38c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 0 0 .95-.69l1.3-3.95Z"/></svg>
        @elseif ($half && $i === $full + 1)
            <svg viewBox="0 0 20 20" class="{{ $size }}" aria-hidden="true">
                <defs><linearGradient id="half-{{ $i }}-{{ crc32($value.$size) }}"><stop offset="50%" stop-color="var(--color-ember-400)"/><stop offset="50%" stop-color="var(--color-ink-200)"/></linearGradient></defs>
                <path fill="url(#half-{{ $i }}-{{ crc32($value.$size) }})" d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.95a1 1 0 0 0 .95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.44a1 1 0 0 0-.36 1.12l1.28 3.95c.3.92-.75 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.79.57-1.84-.2-1.54-1.12l1.28-3.95a1 1 0 0 0-.36-1.12L2.05 9.38c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 0 0 .95-.69l1.3-3.95Z"/>
            </svg>
        @else
            <svg viewBox="0 0 20 20" fill="currentColor" class="{{ $size }} text-ink-200 dark:text-ink-700" aria-hidden="true"><path d="M9.05 2.93c.3-.92 1.6-.92 1.9 0l1.28 3.95a1 1 0 0 0 .95.69h4.16c.97 0 1.37 1.24.59 1.81l-3.37 2.44a1 1 0 0 0-.36 1.12l1.28 3.95c.3.92-.75 1.69-1.54 1.12l-3.36-2.44a1 1 0 0 0-1.18 0l-3.36 2.44c-.79.57-1.84-.2-1.54-1.12l1.28-3.95a1 1 0 0 0-.36-1.12L2.05 9.38c-.78-.57-.38-1.81.6-1.81h4.15a1 1 0 0 0 .95-.69l1.3-3.95Z"/></svg>
        @endif
    @endfor
</span>
