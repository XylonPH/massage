<?php

namespace App\Support\Demo;

/**
 * Temporary synthetic directory content used to render the public pages
 * until the MongoDB-backed directory records exist. Every establishment,
 * person, review, and product below is fictional demo data.
 *
 * Lives under Support/Demo/ (not a feature Action/Service, not a database
 * seeder) because it is placeholder render-time content standing in for a
 * real data layer that does not exist yet, per
 * docs/01-project/file-and-folder-structure.txt section 12. Delete this
 * class and its Support/Demo/ folder once real establishment, therapist,
 * review, and product records exist and the controllers that reference it
 * read from the database instead.
 */
class SampleContent
{
    /** @return array<int, array<string, mixed>> */
    public static function featuredSpas(): array
    {
        return [
            [
                'slug' => 'the-resting-leaf',
                'name' => 'The Resting Leaf',
                'area' => 'Mandaluyong City, Metro Manila',
                'rating' => 4.8,
                'review_count' => 342,
                'services' => 'Signature Relaxation, Aromatherapy, Deep Tissue',
                'closes' => '10:00 PM',
                'is_premium' => true,
                'is_verified' => true,
                'gradient' => 'from-leaf-700 via-leaf-900 to-ink-950',
                'icon' => 'leaf',
            ],
            [
                'slug' => 'lotus-wellness-spa',
                'name' => 'Lotus Wellness Spa',
                'area' => 'Quezon City, Metro Manila',
                'rating' => 4.6,
                'review_count' => 189,
                'services' => 'Ventosa, Reflexology, Thai',
                'closes' => '10:00 PM',
                'is_premium' => true,
                'is_verified' => true,
                'gradient' => 'from-ink-700 via-ink-900 to-charcoal-950',
                'icon' => 'lotus',
            ],
            [
                'slug' => 'haven-of-serenity',
                'name' => 'Haven of Serenity',
                'area' => 'BGC, Taguig City',
                'rating' => 4.5,
                'review_count' => 172,
                'services' => 'Swedish, Deep Tissue, Couples',
                'closes' => '11:30 PM',
                'is_premium' => false,
                'is_verified' => true,
                'gradient' => 'from-ember-700 via-ember-900 to-charcoal-950',
                'icon' => 'stones',
            ],
            [
                'slug' => 'blissful-hands-spa',
                'name' => 'Blissful Hands Spa',
                'area' => 'Pasig City, Metro Manila',
                'rating' => 4.4,
                'review_count' => 143,
                'services' => 'Shiatsu, Hilot, Swedish',
                'closes' => '10:30 PM',
                'is_premium' => false,
                'is_verified' => true,
                'gradient' => 'from-ink-600 via-ink-800 to-leaf-950',
                'icon' => 'hands',
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function featuredTherapists(): array
    {
        return [
            ['name' => 'Maya Santos', 'rating' => 4.9, 'review_count' => 128, 'specialties' => 'Swedish • Deep Tissue', 'area' => 'Makati, Metro Manila', 'availability' => 'today', 'initials' => 'MS', 'tone' => 'bg-leaf-100 text-leaf-700'],
            ['name' => 'Rafael Dela Cruz', 'rating' => 4.9, 'review_count' => 96, 'specialties' => 'Hilot • Ventosa', 'area' => 'Quezon City', 'availability' => 'today', 'initials' => 'RD', 'tone' => 'bg-ink-100 text-ink-700'],
            ['name' => 'Luna Reyes', 'rating' => 4.8, 'review_count' => 110, 'specialties' => 'Aromatherapy • Shiatsu', 'area' => 'Taguig City', 'availability' => 'tomorrow', 'initials' => 'LR', 'tone' => 'bg-ember-100 text-ember-700'],
            ['name' => 'Tara Mendoza', 'rating' => 4.8, 'review_count' => 88, 'specialties' => 'Thai • Hot Stone', 'area' => 'Pasig City', 'availability' => 'today', 'initials' => 'TM', 'tone' => 'bg-ink-100 text-ink-700'],
        ];
    }

    /** @return array<int, string> */
    public static function areas(): array
    {
        return ['Makati City', 'BGC, Taguig', 'Quezon City', 'Pasig City', 'Mandaluyong', 'Manila', 'Parañaque', 'Pasay City'];
    }

    /** @return array<int, array<string, string>> */
    public static function massageTypes(): array
    {
        return [
            ['name' => 'Swedish', 'icon' => 'waves'],
            ['name' => 'Hilot', 'icon' => 'hands'],
            ['name' => 'Thai', 'icon' => 'stretch'],
            ['name' => 'Shiatsu', 'icon' => 'pressure'],
            ['name' => 'Hot Stone', 'icon' => 'stones'],
            ['name' => 'Deep Tissue', 'icon' => 'muscle'],
            ['name' => 'Aromatherapy', 'icon' => 'aroma'],
            ['name' => 'Reflexology', 'icon' => 'foot'],
        ];
    }

    /** @return array<int, array<string, string>> */
    public static function latestArticles(): array
    {
        return [
            ['title' => 'Spa Etiquette: What Every First-Timer Should Know', 'date' => 'Jul 15, 2026', 'category' => 'Spa Guide', 'tone' => 'from-ink-500 to-ink-800'],
            ['title' => 'Benefits of Regular Massage for Body and Mind', 'date' => 'Jul 12, 2026', 'category' => 'Wellness', 'tone' => 'from-leaf-500 to-leaf-800'],
            ['title' => 'How to Choose the Right Massage Therapist', 'date' => 'Jul 9, 2026', 'category' => 'Tips', 'tone' => 'from-ember-500 to-ember-800'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function latestReviews(): array
    {
        return [
            ['reviewer' => 'Micaela R.', 'rating' => 5.0, 'target' => 'The Resting Leaf', 'excerpt' => 'So relaxing and clean. The therapists are very professional — will definitely come back!', 'time' => '2 hours ago', 'initials' => 'MR', 'tone' => 'bg-leaf-100 text-leaf-700'],
            ['reviewer' => 'Jason D.', 'rating' => 5.0, 'target' => 'Haven of Serenity', 'excerpt' => 'Best deep tissue massage I\'ve had in a long time. My back feels so much better now.', 'time' => '5 hours ago', 'initials' => 'JD', 'tone' => 'bg-ink-100 text-ink-700'],
            ['reviewer' => 'Ana P.', 'rating' => 4.5, 'target' => 'Lotus Wellness Spa', 'excerpt' => 'Love their aromatherapy massage and the warm ambiance. Highly recommended!', 'time' => 'Yesterday', 'initials' => 'AP', 'tone' => 'bg-ember-100 text-ember-700'],
        ];
    }

    /** @return array<int, array<string, string>> */
    public static function promos(): array
    {
        return [
            ['offer' => '15% Off', 'business' => 'The Resting Leaf', 'description' => 'Weekday aromatherapy sessions before 3 PM', 'valid_until' => 'Jul 31, 2026', 'tone' => 'from-ember-500 to-ember-700'],
            ['offer' => 'Free Upgrade', 'business' => 'Lotus Wellness Spa', 'description' => '60 → 90 mins on first Ventosa booking', 'valid_until' => 'Aug 15, 2026', 'tone' => 'from-leaf-500 to-leaf-700'],
            ['offer' => 'Couples Deal', 'business' => 'Haven of Serenity', 'description' => 'Two Swedish massages with a private room', 'valid_until' => 'Aug 8, 2026', 'tone' => 'from-ink-500 to-ink-700'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function wellnessFinds(): array
    {
        return [
            ['name' => 'Lavender Massage Oil 250ml', 'price' => '₱495', 'rating' => 4.7, 'icon' => 'aroma', 'tone' => 'from-ink-100 to-ink-200 text-ink-600'],
            ['name' => 'Basalt Hot Stone Set (8 pc)', 'price' => '₱1,890', 'rating' => 4.8, 'icon' => 'stones', 'tone' => 'from-ember-100 to-ember-200 text-ember-700'],
            ['name' => 'Bamboo Eye Pillow', 'price' => '₱350', 'rating' => 4.6, 'icon' => 'leaf', 'tone' => 'from-leaf-100 to-leaf-200 text-leaf-700'],
            ['name' => 'Herbal Compress Balls (2 pc)', 'price' => '₱620', 'rating' => 4.5, 'icon' => 'lotus', 'tone' => 'from-ink-100 to-leaf-100 text-leaf-700'],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function trendingSpas(): array
    {
        return [
            ['rank' => 1, 'name' => 'The Resting Leaf', 'slug' => 'the-resting-leaf', 'area' => 'Mandaluyong', 'rating' => 4.8, 'review_count' => 342],
            ['rank' => 2, 'name' => 'Lotus Wellness Spa', 'slug' => 'lotus-wellness-spa', 'area' => 'Quezon City', 'rating' => 4.6, 'review_count' => 189],
            ['rank' => 3, 'name' => 'Haven of Serenity', 'slug' => 'haven-of-serenity', 'area' => 'BGC, Taguig', 'rating' => 4.5, 'review_count' => 172],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    public static function trendingTherapists(): array
    {
        return [
            ['rank' => 1, 'name' => 'Maya Santos', 'area' => 'Makati', 'rating' => 4.9, 'review_count' => 128, 'initials' => 'MS', 'tone' => 'bg-leaf-100 text-leaf-700'],
            ['rank' => 2, 'name' => 'Luna Reyes', 'area' => 'Taguig City', 'rating' => 4.8, 'review_count' => 110, 'initials' => 'LR', 'tone' => 'bg-ember-100 text-ember-700'],
            ['rank' => 3, 'name' => 'Rafael Dela Cruz', 'area' => 'Quezon City', 'rating' => 4.9, 'review_count' => 96, 'initials' => 'RD', 'tone' => 'bg-ink-100 text-ink-700'],
        ];
    }

    /** @return array<int, array<string, string>> */
    public static function newListings(): array
    {
        return [
            ['name' => 'Kalma Spa Studio', 'type' => 'Spa', 'area' => 'Marikina City', 'added' => 'Jul 16, 2026'],
            ['name' => 'Dennis Aquino', 'type' => 'Therapist', 'area' => 'Home service, Cavite', 'added' => 'Jul 15, 2026'],
            ['name' => 'Siglo Wellness Lounge', 'type' => 'Spa', 'area' => 'Alabang, Muntinlupa', 'added' => 'Jul 14, 2026'],
        ];
    }

    /** @return array<int, array<string, string>> */
    public static function updatedProfiles(): array
    {
        return [
            ['name' => 'Lotus Wellness Spa', 'change' => 'New service menu and updated hours', 'updated' => 'Jul 17, 2026'],
            ['name' => 'Blissful Hands Spa', 'change' => 'Added 6 new photos and parking details', 'updated' => 'Jul 16, 2026'],
            ['name' => 'Haven of Serenity', 'change' => 'Now verified • updated contact details', 'updated' => 'Jul 15, 2026'],
        ];
    }

    /** @return array<string, string> */
    public static function stats(): array
    {
        return [
            'spas' => '10,240+',
            'therapists' => '21,580+',
            'reviews' => '58,720+',
            'articles' => '5,320+',
        ];
    }

    /** @return array<string, string> */
    public static function quote(): array
    {
        return [
            'text' => 'Your body hears everything your mind says.',
            'author' => 'Naomi Judd',
        ];
    }

    /**
     * Full sample profile for the in-universe demo spa.
     *
     * @return array<string, mixed>
     */
    public static function spaProfile(string $slug): ?array
    {
        $profiles = [
            'the-resting-leaf' => [
                'slug' => 'the-resting-leaf',
                'name' => 'The Resting Leaf',
                'type' => 'Wellness Center',
                'market_class' => 'Standard',
                'area' => 'Mandaluyong City, Metro Manila',
                'rating' => 4.8,
                'review_count' => 342,
                'is_verified' => true,
                'is_claimed' => true,
                'is_open' => true,
                'closes' => '10:00 PM',
                'photo_count' => 18,
                'last_confirmed' => 'Jul 10, 2026',
                'about' => 'A calm urban sanctuary offering professional massage therapies and wellness treatments designed to restore balance to your body and mind. Our peaceful space and skilled therapists help you relax, recharge, and feel your best.',
                'badges' => ['home_service', 'gift_certificates', 'resorts_relaxation'],
                'services' => [
                    ['name' => 'Signature Relaxation Massage', 'duration' => 60, 'price' => '₱950', 'icon' => 'leaf', 'tone' => 'from-leaf-100 to-leaf-200 text-leaf-700'],
                    ['name' => 'Aromatherapy Massage', 'duration' => 90, 'price' => '₱1,350', 'icon' => 'aroma', 'tone' => 'from-ink-100 to-ink-200 text-ink-600'],
                    ['name' => 'Deep Tissue Massage', 'duration' => 90, 'price' => '₱1,450', 'icon' => 'muscle', 'tone' => 'from-ember-100 to-ember-200 text-ember-700'],
                    ['name' => 'Hot Stone Therapy', 'duration' => 75, 'price' => '₱1,600', 'icon' => 'stones', 'tone' => 'from-charcoal-800/10 to-ink-200 text-charcoal-800'],
                ],
                'therapists' => [
                    ['name' => 'Amara Villanueva', 'title' => 'Senior Therapist', 'specialties' => 'Aromatherapy • Swedish', 'rating' => 4.9, 'initials' => 'AV', 'tone' => 'bg-leaf-100 text-leaf-700'],
                    ['name' => 'Marco Lim', 'title' => 'Deep Tissue Specialist', 'specialties' => 'Deep Tissue • Sports', 'rating' => 4.8, 'initials' => 'ML', 'tone' => 'bg-ink-100 text-ink-700'],
                    ['name' => 'Rosa Fernandez', 'title' => 'Master Reflexologist', 'specialties' => 'Reflexology • Hilot', 'rating' => 4.9, 'initials' => 'RF', 'tone' => 'bg-ember-100 text-ember-700'],
                ],
                'amenities' => [
                    ['label' => 'Shower', 'icon' => 'shower'],
                    ['label' => 'Wi-Fi', 'icon' => 'wifi'],
                    ['label' => 'Parking', 'icon' => 'parking'],
                    ['label' => 'Credit Cards', 'icon' => 'card'],
                    ['label' => 'Lockers', 'icon' => 'locker'],
                    ['label' => 'Refreshments', 'icon' => 'tea'],
                ],
                'treatment_areas' => [
                    ['name' => 'Garden Suite', 'privacy' => 'private', 'capacity' => 'couple', 'note' => 'VIP room with rain shower'],
                    ['name' => 'Bamboo Rooms 1–4', 'privacy' => 'private', 'capacity' => 'individual', 'note' => 'Enclosed single rooms'],
                    ['name' => 'Lounge Stations', 'privacy' => 'semi_private', 'capacity' => 'group', 'note' => 'Reflexology recliners'],
                ],
                'address' => 'Unit 3A, 2F, Luna Bldg., Pineda St. cor. Shaw Blvd., Mandaluyong City 1550',
                'landmarks' => [
                    ['name' => 'SM Megamall', 'minutes' => 5],
                    ['name' => 'Robinsons Pioneer', 'minutes' => 7],
                    ['name' => 'Shaw MRT Station', 'minutes' => 5],
                ],
                'reviews' => [
                    ['reviewer' => 'Micaela R.', 'date' => 'Jul 10, 2026', 'rating' => 5.0, 'text' => 'The place is so relaxing and clean. The therapists are very professional. Will definitely come back!', 'initials' => 'MR', 'tone' => 'bg-leaf-100 text-leaf-700'],
                    ['reviewer' => 'Jason D.', 'date' => 'Jul 9, 2026', 'rating' => 5.0, 'text' => 'Best deep tissue massage I\'ve had in a long time. My back feels so much better now.', 'initials' => 'JD', 'tone' => 'bg-ink-100 text-ink-700'],
                    ['reviewer' => 'Ana P.', 'date' => 'Jul 7, 2026', 'rating' => 4.5, 'text' => 'Love their aromatherapy massage and the warm ambiance. Highly recommended!', 'initials' => 'AP', 'tone' => 'bg-ember-100 text-ember-700'],
                ],
                'booking_times' => ['10:00 AM', '11:30 AM', '1:00 PM', '2:30 PM', '4:00 PM', '5:30 PM', '7:00 PM', '8:30 PM'],
            ],
        ];

        return $profiles[$slug] ?? null;
    }
}
