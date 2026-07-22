<?php

namespace App\Enums;

enum ArticleCategory: string
{
    case PlatformCommunity = 'PCI';
    case FirstTime = 'FTM';
    case ChoosingBookingReviews = 'CBR';
    case SafetyBoundariesTrust = 'SBT';
    case HygieneComfort = 'HYG';
    case MassageTechniques = 'MTT';
    case PressureRecovery = 'PRA';
    case BodyPostureStress = 'BPS';
    case WellnessSelfCare = 'WSC';
    case OilsProductsHome = 'OPH';
    case SpecialCare = 'SAC';
    case LocalGlobalCulture = 'LPG';
    case SpaBusiness = 'SBO';
    case PractitionerCareer = 'PPC';
    case FictionSeasonal = 'FSP';

    public function label(): string
    {
        return match ($this) {
            self::PlatformCommunity => 'Platform and Community Identity',
            self::FirstTime => 'First-Time Massage and Spa Etiquette',
            self::ChoosingBookingReviews => 'Choosing Spas, Booking, Pricing and Reviews',
            self::SafetyBoundariesTrust => 'Safety, Boundaries and Professional Trust',
            self::HygieneComfort => 'Hygiene, Cleanliness and Service Comfort',
            self::MassageTechniques => 'Massage Types, Techniques and Traditions',
            self::PressureRecovery => 'Pressure, Pain, Recovery and Aftercare',
            self::BodyPostureStress => 'Body, Posture and Stress Science',
            self::WellnessSelfCare => 'Everyday Wellness, Sleep and Self-Care',
            self::OilsProductsHome => 'Oils, Products and Home Wellness Tools',
            self::SpecialCare => 'Special Care, Accessibility and Caregiving',
            self::LocalGlobalCulture => 'Local Practice, Credentials and Global Spa Culture',
            self::SpaBusiness => 'Spa Business, Design and Operations',
            self::PractitionerCareer => 'Practitioner Practice and Career Development',
            self::FictionSeasonal => 'Fictional, Seasonal and Pop-Culture Features',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::PlatformCommunity => 'Articles about Massage Nexus as a platform — launch notes, editorial identity, brand-world content, community purpose, and how the site positions itself in the wellness space.',
            self::FirstTime => 'Beginner-friendly guides covering what to expect before, during, and after a massage. Includes advice on manners, comfort, clothing, tipping, and normal spa behavior.',
            self::ChoosingBookingReviews => 'Decision-making articles that help readers compare spas, understand pricing, book appointments wisely, read reviews critically, and avoid poor-fit choices.',
            self::SafetyBoundariesTrust => 'Articles about consent, professional limits, ethical conduct, red flags, and protecting the dignity and safety of both clients and practitioners.',
            self::HygieneComfort => 'Practical guides on linens, room cleanliness, oils, sanitation, skin concerns, bodily noises, and the visible signals that tell you a service environment is well maintained.',
            self::MassageTechniques => 'Plain-language explainers about massage modalities, bodywork traditions, technique differences, and how to choose a style that suits your needs.',
            self::PressureRecovery => 'Articles covering pressure preferences, post-session soreness, hydration, heat and ice, exercise timing, and knowing when discomfort during or after a massage is not normal.',
            self::BodyPostureStress => 'Accessible body-education content on muscles, fascia, posture, breathing, nervous-system stress, inflammation, circulation, and how the body recovers.',
            self::WellnessSelfCare => 'Habit-based articles about burnout, sleep, digital fatigue, rest routines, and small daily resets that support wellbeing between spa visits.',
            self::OilsProductsHome => 'Guides on massage oils, balms, aromatherapy, hydrotherapy tools, home-care supplies, and practical wellness products for everyday use.',
            self::SpecialCare => 'Content focused on pregnancy, seniors, children, disability, limited mobility, illness recovery, and caregiving — all centered on dignity and appropriate, safe touch.',
            self::LocalGlobalCulture => 'Articles exploring local massage terms, credentials, cultural practices, travel etiquette, international spa customs, and what to expect when visiting wellness venues abroad.',
            self::SpaBusiness => 'Business-facing content about starting, managing, pricing, staffing, marketing, and improving spa or massage establishment operations.',
            self::PractitionerCareer => 'Practitioner-focused articles on technique refinement, ergonomics, professional boundaries, client communication, safety, and career development.',
            self::FictionSeasonal => 'Imaginative, seasonal, comic-adjacent, or pop-culture articles that connect clearly to massage, touch, recovery, or wellness in an entertaining way.',
        };
    }

    public function slug(): string
    {
        return match ($this) {
            self::PlatformCommunity => 'platform-and-community-identity',
            self::FirstTime => 'first-time-massage-and-spa-etiquette',
            self::ChoosingBookingReviews => 'choosing-spas-booking-pricing-and-reviews',
            self::SafetyBoundariesTrust => 'safety-boundaries-and-professional-trust',
            self::HygieneComfort => 'hygiene-cleanliness-and-service-comfort',
            self::MassageTechniques => 'massage-types-techniques-and-traditions',
            self::PressureRecovery => 'pressure-pain-recovery-and-aftercare',
            self::BodyPostureStress => 'body-posture-and-stress-science',
            self::WellnessSelfCare => 'everyday-wellness-sleep-and-self-care',
            self::OilsProductsHome => 'oils-products-and-home-wellness-tools',
            self::SpecialCare => 'special-care-accessibility-and-caregiving',
            self::LocalGlobalCulture => 'local-practice-credentials-and-global-spa-culture',
            self::SpaBusiness => 'spa-business-design-and-operations',
            self::PractitionerCareer => 'practitioner-practice-and-career-development',
            self::FictionSeasonal => 'fictional-seasonal-and-pop-culture-features',
        };
    }

    public function iconName(): string
    {
        return match ($this) {
            self::PlatformCommunity => 'platform-community',
            self::FirstTime => 'first-time',
            self::ChoosingBookingReviews => 'choosing-booking',
            self::SafetyBoundariesTrust => 'safety-trust',
            self::HygieneComfort => 'hygiene-comfort',
            self::MassageTechniques => 'massage-techniques',
            self::PressureRecovery => 'pressure-recovery',
            self::BodyPostureStress => 'body-posture',
            self::WellnessSelfCare => 'wellness-self-care',
            self::OilsProductsHome => 'oils-products',
            self::SpecialCare => 'special-care',
            self::LocalGlobalCulture => 'local-global-culture',
            self::SpaBusiness => 'spa-business',
            self::PractitionerCareer => 'practitioner-career',
            self::FictionSeasonal => 'fiction-seasonal',
        };
    }

    public static function fromSlug(string $slug): ?self
    {
        foreach (self::cases() as $case) {
            if ($case->slug() === $slug) {
                return $case;
            }
        }

        return null;
    }
}
