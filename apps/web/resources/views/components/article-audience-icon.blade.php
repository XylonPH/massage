@props(['audience', 'class' => 'size-5'])

@php
    use App\Enums\ArticleAudience;

    $iconKey = match (true) {
        $audience instanceof ArticleAudience => $audience->iconName(),
        is_string($audience) && ($enum = ArticleAudience::tryFrom($audience)) !== null => $enum->iconName(),
        is_string($audience) && ($enum = ArticleAudience::fromSlug($audience)) !== null => $enum->iconName(),
        default => (string) $audience,
    };
@endphp

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    @switch($iconKey)
        @case('general')
            {{-- General: Public readers / group of users --}}
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            @break

        @case('client')
            {{-- Client: Spa guest profile with heart badge --}}
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
            <path d="M18.5 4.5c.8-.8 2-.8 2.8 0s.8 2 0 2.8L18.5 10l-2.8-2.7c-.8-.8-.8-2 0-2.8s2-.8 2.8 0Z" fill="currentColor" stroke="none" />
            @break

        @case('practitioner')
            {{-- Practitioner: Massage therapist profile with healing hands spark --}}
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M18 10V6.5a1 1 0 0 1 2 0V11m0-3a1 1 0 0 1 2 0V13c0 2.5-1.5 4-4 4" />
            @break

        @case('owner')
            {{-- Owner: Spa business owner building facade with key emblem --}}
            <path d="M3 21h18" />
            <path d="M5 21V9l7-5 7 5v12" />
            <path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4" />
            <circle cx="12" cy="9" r="1.5" />
            <path d="M12 10.5V13" />
            @break

        @case('caregiver')
            {{-- Caregiver: Holding hands with supportive heart --}}
            <path d="M12 18s-5.5-3.8-5.5-7.5A3 3 0 0 1 12 8a3 3 0 0 1 5.5 2.5C17.5 14.2 12 18 12 18Z" fill="currentColor" stroke="none" />
            <path d="M4 13.5c.5 2 2.5 4.5 5.5 5.5" />
            <path d="M20 13.5c-.5 2-2.5 4.5-5.5 5.5" />
            @break

        @case('traveler')
            {{-- Traveler: Compass & globe traveler --}}
            <circle cx="12" cy="12" r="9" />
            <polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76" />
            @break

        @default
            {{-- Fallback: User icon --}}
            <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
            <circle cx="12" cy="7" r="4" />
    @endswitch
</svg>
