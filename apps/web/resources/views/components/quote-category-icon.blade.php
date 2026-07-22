@props(['category', 'class' => 'size-4'])

@php
    use App\Enums\QuoteCategory;

    $iconKey = match (true) {
        $category instanceof QuoteCategory => $category->iconName(),
        is_string($category) && ($enum = QuoteCategory::tryFrom($category)) !== null => $enum->iconName(),
        default => (string) $category,
    };
@endphp

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    @switch($iconKey)
        @case('moon')
            {{-- Rest and Relaxation: Crescent moon & serene star --}}
            <path d="M12 3a9 9 0 1 0 9 9c0-.46-.04-.92-.1-1.36a5.38 5.38 0 0 1-6.54-6.54c-.44-.06-.9-.1-1.36-.1Z" />
            <circle cx="18" cy="5" r="1" fill="currentColor" />
            @break

        @case('lotus')
            {{-- Mindfulness and Presence: Lotus bloom --}}
            <path d="M12 21c-2.5-1.5-4-4-4-7 0-2.5 1.5-5.5 4-8 2.5 2.5 4 5.5 4 8 0 3-1.5 5.5-4 7Z" />
            <path d="M12 21c-3.5.5-6.5-.5-8.5-3 1.5-1 3-1.5 4.5-1.5" />
            <path d="M12 21c3.5.5 6.5-.5 8.5-3-1.5-1-3-1.5-4.5-1.5" />
            @break

        @case('brain-heart')
            {{-- Mental and Emotional Wellness: Mind with heart center --}}
            <path d="M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z" />
            <path d="M12 15s-3.5-2.5-3.5-5a2 2 0 0 1 3.5-1.3A2 2 0 0 1 15.5 10c0 2.5-3.5 5-3.5 5Z" fill="currentColor" />
            @break

        @case('sparkles')
            {{-- Physical Health and Vitality: Vitality spark --}}
            <path d="M12 2v4m0 12v4M2 12h4m12 0h4" />
            <path d="m4.93 4.93 2.83 2.83m8.48 8.48 2.83 2.83M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83" />
            @break

        @case('hands-holding')
            {{-- Healing and Recovery: Healing hands & restorative heart --}}
            <path d="M12 13s-3-2-3-4a2 2 0 0 1 3.5-1.2A2 2 0 0 1 15 9c0 2-3 4-3 4Z" fill="currentColor" />
            <path d="M4 17c1.5 2 4.5 3 8 3s6.5-1 8-3" />
            <path d="M2 12c1.5 1.5 4 2.5 7 2.5" />
            <path d="M22 12c-1.5 1.5-4 2.5-7 2.5" />
            @break

        @case('scale')
            {{-- Self-Care and Balance: Harmony balance --}}
            <path d="M12 3v18M5 8h14M5 8l-3 7h6L5 8Zm14 0l-3 7h6l-3-7Z" />
            @break

        @case('sun-rising')
            {{-- Resilience and Motivation: Rising sun & rays --}}
            <path d="M12 16a5 5 0 0 0 5-5H7a5 5 0 0 0 5 5Z" />
            <path d="M12 3v3M4.93 6.93l2.12 2.12M19.07 6.93l-2.12 2.12M2 16h20" />
            @break

        @case('heart')
            {{-- Compassion and Connection: Interlocking hearts --}}
            <path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
            @break

        @case('leaf')
            {{-- Spiritual Reflection: Peaceful leaf --}}
            <path d="M11 20A9 9 0 0 0 20 11V3h-8a9 9 0 0 0-9 9c0 2 1 3.5 2 5.5Z" />
            <path d="M11 20c-1 0-3 1-4 2" />
            <path d="M11 13c3-3 6-5 9-6" />
            @break

        @default
            {{-- Default Leaf --}}
            <path d="M11 20A9 9 0 0 0 20 11V3h-8a9 9 0 0 0-9 9c0 2 1 3.5 2 5.5Z" />
            <path d="M11 13c3-3 6-5 9-6" />
    @endswitch
</svg>
