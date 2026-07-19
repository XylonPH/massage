@props(['dark' => false, 'wordmark' => true, 'size' => 'h-10'])

@php
    // Drop-in brand assets take priority over the built-in fallback mark.
    // Place exported files under public/images/brand/ using these names:
    //   logo-full.svg|png|webp       full lockup (mark + wordmark) for light backgrounds
    //   logo-full-dark.svg|png|webp  full lockup for dark backgrounds
    //   logo-mark.svg|png|webp       square mark only (used when $wordmark is false)
    $brandFile = function (string $stem): ?string {
        foreach (['svg', 'png', 'webp'] as $extension) {
            if (file_exists(public_path("images/brand/{$stem}.{$extension}"))) {
                return asset("images/brand/{$stem}.{$extension}");
            }
        }

        return null;
    };

    $mark = $brandFile('logo-mark');
    $full = $dark ? ($brandFile('logo-full-dark') ?? $brandFile('logo-full')) : $brandFile('logo-full');
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-2.5']) }}>
    @if ($wordmark && $full)
        <img src="{{ $full }}" alt="{{ config('app.name') }}" class="{{ $size }} w-auto shrink-0">
    @else
        @if ($mark)
            <img src="{{ $mark }}" alt="{{ config('app.name') }}" class="{{ $size }} w-auto shrink-0">
        @else
            <svg viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" class="{{ $size }} w-auto shrink-0" role="img" aria-label="{{ config('app.name') }}">
                {{-- Outer ink crescent --}}
                <path d="M 88 17 A 52 52 0 1 0 100 92" stroke="{{ $dark ? '#96b1d4' : '#16294a' }}" stroke-width="7.5" stroke-linecap="round"/>
                {{-- Flowing body line --}}
                <path d="M 44 22 C 36 36 50 44 42 58 C 36 69 42 76 40 84" stroke="{{ $dark ? '#c3d3e8' : '#26426e' }}" stroke-width="5" stroke-linecap="round"/>
                {{-- Spine dots --}}
                <circle cx="28" cy="38" r="2.2" fill="#f96410"/>
                <circle cx="26.5" cy="47" r="2.8" fill="#f96410"/>
                <circle cx="26" cy="56.5" r="3.4" fill="#f96410"/>
                <circle cx="27" cy="66.5" r="4" fill="#f96410"/>
                {{-- Node network --}}
                <path d="M 68 44 V 30 M 68 72 V 86 M 54 58 H 44 M 82 58 H 92" stroke="#f96410" stroke-width="4.5" stroke-linecap="round"/>
                <circle cx="68" cy="58" r="11" stroke="#f96410" stroke-width="5.5"/>
                <circle cx="68" cy="27" r="6.5" fill="{{ $dark ? '#96b1d4' : '#16294a' }}"/>
                <circle cx="68" cy="88" r="5.5" fill="{{ $dark ? '#96b1d4' : '#16294a' }}"/>
                <circle cx="40" cy="58" r="6.5" fill="{{ $dark ? '#e2eaf5' : '#2b2f33' }}"/>
                <circle cx="96" cy="58" r="6.5" fill="#3e914a"/>
                {{-- Leaf --}}
                <path d="M 92 12 C 104 14 110 24 108 36 C 96 34 90 24 92 12 Z" fill="#3e914a"/>
                <path d="M 94 15 C 100 22 104 28 106 33" stroke="{{ $dark ? '#0b1830' : '#ffffff' }}" stroke-width="2" stroke-linecap="round"/>
                {{-- Supporting hand --}}
                <path d="M 30 88 C 42 82 56 84 68 88 C 80 92 90 90 97 84 C 96 96 84 105 68 105 C 50 105 36 98 30 88 Z" fill="{{ $dark ? '#e2eaf5' : '#1f2225' }}"/>
            </svg>
        @endif
        @if ($wordmark)
            <span class="flex flex-col leading-none">
                <span class="text-[1.05rem] font-bold tracking-tight {{ $dark ? 'text-white' : 'text-ink-900' }}">Massage</span>
                <span class="text-[1.05rem] font-bold tracking-tight {{ $dark ? 'text-white' : 'text-ink-900' }}">Ne<span class="text-ember-500">x</span>us</span>
            </span>
        @endif
    @endif
</span>
