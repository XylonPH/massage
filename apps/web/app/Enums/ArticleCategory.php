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
