@props(['category', 'class' => 'size-5'])

@php
    use App\Enums\ArticleCategory;

    $iconKey = match (true) {
        $category instanceof ArticleCategory => $category->iconName(),
        is_string($category) && ($enum = ArticleCategory::tryFrom($category)) !== null => $enum->iconName(),
        is_string($category) && ($enum = ArticleCategory::fromSlug($category)) !== null => $enum->iconName(),
        default => (string) $category,
    };
@endphp

<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" {{ $attributes->merge(['class' => $class]) }} aria-hidden="true">
    @switch($iconKey)
        @case('platform-community')
            {{-- Platform and Community Identity: Interconnected lotus & community node spark --}}
            <path d="M12 21c-2.5-1.5-4-4-4-7 0-2.5 1.5-5.5 4-8 2.5 2.5 4 5.5 4 8 0 3-1.5 5.5-4 7Z" />
            <path d="M12 21c-3.5.5-6.5-.5-8.5-3 1.5-1 3-1.5 4.5-1.5" />
            <path d="M12 21c3.5.5 6.5-.5 8.5-3-1.5-1-3-1.5-4.5-1.5" />
            <circle cx="12" cy="4" r="1.5" fill="currentColor" />
            <circle cx="4" cy="12" r="1.2" fill="currentColor" />
            <circle cx="20" cy="12" r="1.2" fill="currentColor" />
            @break

        @case('first-time')
            {{-- First-Time Massage and Spa Etiquette: Welcoming spa door with guiding spark --}}
            <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16" />
            <path d="M9 21v-8a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v8" />
            <path d="M12 7h.01" />
            <path d="M3 21h18" />
            <path d="M17 11.5l1.5-1.5m0 0L20 8.5m-1.5 1.5L20 11.5m-1.5-1.5L17 8.5" />
            @break

        @case('choosing-booking')
            {{-- Choosing Spas, Booking, Pricing and Reviews: Calendar with star rating badge & tag --}}
            <path d="M8 2v3m8-3v3" />
            <rect x="3" y="4" width="18" height="17" rx="2.5" />
            <path d="M3 9h18" />
            <path d="M12 12.5l.8 1.6 1.8.3-1.3 1.3.3 1.8-1.6-.8-1.6.8.3-1.8-1.3-1.3 1.8-.3z" fill="currentColor" />
            @break

        @case('safety-trust')
            {{-- Safety, Boundaries and Professional Trust: Shield with heart and verification check --}}
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10Z" />
            <path d="M12 8.5c-1.2-1.2-3.1-1.2-4.2 0s-1.2 3.1 0 4.2L12 17l4.2-4.3c1.2-1.1 1.2-3 0-4.2s-3-1.1-4.2 0Z" />
            @break

        @case('hygiene-comfort')
            {{-- Hygiene, Cleanliness and Service Comfort: Clean droplet over folded linen towel --}}
            <path d="M12 2.5C12 2.5 6 8.5 6 12.5a6 6 0 0 0 12 0c0-4-6-10-6-10Z" />
            <path d="M4 18h16a2 2 0 0 1 2 2v0a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1v0a2 2 0 0 1 2-2Z" />
            <path d="M15 6.5l1-1m1 4l1.5-.5" />
            @break

        @case('massage-techniques')
            {{-- Massage Types, Techniques and Traditions: Therapeutic hands applying pressure waves --}}
            <path d="M6 12V7a1.5 1.5 0 0 1 3 0v4.5m0-4.5V5.5a1.5 1.5 0 0 1 3 0V11m0-5.5a1.5 1.5 0 0 1 3 0V12m0-4.5a1.5 1.5 0 0 1 3 0V14c0 3.5-2 5.5-5 5.5H11c-3 0-5-2-5-5v-2" />
            <path d="M3 21c3-2 6-2 9 0s6 2 9 0" />
            @break

        @case('pressure-recovery')
            {{-- Pressure, Pain, Recovery and Aftercare: Concentric pressure target & soothing wave --}}
            <circle cx="12" cy="11" r="7" />
            <circle cx="12" cy="11" r="3" />
            <circle cx="12" cy="11" r="1" fill="currentColor" />
            <path d="M3 20c3-1.5 6-1.5 9 0s6 1.5 9 0" />
            @break

        @case('body-posture')
            {{-- Body, Posture and Stress Science: Ergonomic posture alignment spine column --}}
            <circle cx="12" cy="3.5" r="1.8" />
            <path d="M12 6v14" />
            <path d="M8 9.5c2-1 6-1 8 0" />
            <path d="M9 13.5c1.5-.8 4.5-.8 6 0" />
            <path d="M8.5 17.5c2.3-1 4.7-1 7 0" />
            <path d="M5 21h14" />
            @break

        @case('wellness-self-care')
            {{-- Everyday Wellness, Sleep and Self-Care: Crescent moon, rest stars & tea leaf --}}
            <path d="M12 3a9 9 0 1 0 9 9c0-.46-.04-.92-.1-1.36a5.38 5.38 0 0 1-6.54-6.54c-.44-.06-.9-.1-1.36-.1Z" />
            <path d="M18 4l.8 1.6L20.4 6l-1.3 1.2.3 1.8-1.6-.9-1.6.9.3-1.8L15 6l1.8-.4L18 4Z" fill="currentColor" />
            @break

        @case('oils-products')
            {{-- Oils, Products and Home Wellness Tools: Essential oil bottle & droplet --}}
            <rect x="7" y="9" width="10" height="12" rx="2" />
            <path d="M9 9V5.5a1.5 1.5 0 0 1 1.5-1.5h3A1.5 1.5 0 0 1 15 5.5V9" />
            <path d="M10 3h4" />
            <path d="M12 12c-1.5 2-1.5 3 0 4.5 1.5-1.5 1.5-2.5 0-4.5Z" fill="currentColor" />
            @break

        @case('special-care')
            {{-- Special Care, Accessibility and Caregiving: Caring hands surrounding heart --}}
            <path d="M12 19s-6-4.3-6-8.5A3.5 3.5 0 0 1 12 8a3.5 3.5 0 0 1 6 2.5C18 14.7 12 19 12 19Z" />
            <path d="M4 14.5c.5 2 2.5 4.5 5.5 5.5" />
            <path d="M20 14.5c-.5 2-2.5 4.5-5.5 5.5" />
            @break

        @case('local-global-culture')
            {{-- Local Practice, Credentials and Global Spa Culture: Globe with seal ribbon --}}
            <circle cx="12" cy="12" r="9" />
            <path d="M3.6 9h16.8M3.6 15h16.8" />
            <path d="M11.5 3a13 13 0 0 0 0 18M12.5 3a13 13 0 0 1 0 18" />
            @break

        @case('spa-business')
            {{-- Spa Business, Design and Operations: Spa facade with growth chart --}}
            <path d="M3 21h18" />
            <path d="M5 21V9l7-5 7 5v12" />
            <path d="M9 21v-4a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v4" />
            <path d="M8 11h2m4 0h2" />
            <path d="M18 3l3 3m0-3v3h-3" />
            @break

        @case('practitioner-career')
            {{-- Practitioner Practice and Career Development: Practitioner profile badge & growth arrow --}}
            <path d="M16 21v-2a4 4 0 0 0-4-4H7a4 4 0 0 0-4 4v2" />
            <circle cx="9.5" cy="7" r="3.5" />
            <path d="M16 10l4-4m0 0h-3m3 0v3" />
            @break

        @case('fiction-seasonal')
            {{-- Fictional, Seasonal and Pop-Culture Features: Magic wand & starburst sparks --}}
            <path d="M14.5 9.5L4 20" />
            <path d="M8 4l1.2 2.4L11.6 7 9.2 8.2 8 10.6 6.8 8.2 4.4 7l2.4-.6L8 4Z" fill="currentColor" />
            <path d="M17 3l.8 1.6L19.4 5l-1.6.8L17 7.4l-.8-1.6L14.6 5l1.6-.4L17 3Z" fill="currentColor" />
            <path d="M18 13l.8 1.6L20.4 15l-1.6.8L18 17.4l-.8-1.6L15.6 15l1.6-.4L18 13Z" fill="currentColor" />
            @break

        @default
            {{-- Fallback: Leaf emblem --}}
            <path d="M6 15C6 8 11 4 19 4c0 8-4 13-11 13-1 0-2-.5-2-2Zm0 0c0 2-1 3-2 5m4-8c2-1 5-3 7-6" />
    @endswitch
</svg>
