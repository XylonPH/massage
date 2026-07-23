<?php

namespace App\Enums;

enum QuoteCategory: string
{
    case RestRelaxation = 'RRL';
    case MindfulnessPresence = 'MNP';
    case MentalEmotionalWellness = 'MEW';
    case PhysicalHealthVitality = 'PHV';
    case HealingRecovery = 'HRC';
    case SelfCareBalance = 'SCB';
    case ResilienceMotivation = 'RSM';
    case CompassionConnection = 'CNC';
    case SpiritualReflection = 'SPR';

    public function getLabel(): string
    {
        return match ($this) {
            self::RestRelaxation => 'Rest and Relaxation',
            self::MindfulnessPresence => 'Mindfulness and Presence',
            self::MentalEmotionalWellness => 'Mental and Emotional Wellness',
            self::PhysicalHealthVitality => 'Physical Health and Vitality',
            self::HealingRecovery => 'Healing and Recovery',
            self::SelfCareBalance => 'Self-Care and Balance',
            self::ResilienceMotivation => 'Resilience and Motivation',
            self::CompassionConnection => 'Compassion and Connection',
            self::SpiritualReflection => 'Spiritual Reflection',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::RestRelaxation => 'Quotes focused on rest, stillness, decompression, and quiet relaxation.',
            self::MindfulnessPresence => 'Quotes centered on being present, awareness, breath, and inner calm.',
            self::MentalEmotionalWellness => 'Quotes encouraging mental health, emotional clarity, peace of mind, and stress reduction.',
            self::PhysicalHealthVitality => 'Quotes about body awareness, posture, energy, physical wellness, and vitality.',
            self::HealingRecovery => 'Quotes on body recovery, therapeutic healing, gentle care, and restoration.',
            self::SelfCareBalance => 'Quotes about personal boundaries, self-kindness, lifestyle balance, and daily self-care.',
            self::ResilienceMotivation => 'Quotes inspiring inner strength, perseverance, positive outlook, and motivation.',
            self::CompassionConnection => 'Quotes highlighting human warmth, empathy, touch, community, and connection.',
            self::SpiritualReflection => 'Quotes offering deeper reflection, gratitude, harmony, and contemplative wisdom.',
        };
    }

    public function iconName(): string
    {
        return match ($this) {
            self::RestRelaxation => 'moon',
            self::MindfulnessPresence => 'lotus',
            self::MentalEmotionalWellness => 'brain-heart',
            self::PhysicalHealthVitality => 'sparkles',
            self::HealingRecovery => 'hands-holding',
            self::SelfCareBalance => 'scale',
            self::ResilienceMotivation => 'sun-rising',
            self::CompassionConnection => 'heart',
            self::SpiritualReflection => 'leaf',
        };
    }

    /**
     * Light and dark mode background styling for Quote of the Day panel.
     */
    public function panelGradientClass(): string
    {
        return match ($this) {
            self::RestRelaxation => 'from-leaf-50/90 via-ink-50/70 to-leaf-100/50 dark:from-leaf-950/90 dark:via-ink-950/80 dark:to-charcoal-950/90 border-leaf-200/80 dark:border-leaf-800/60',
            self::MindfulnessPresence => 'from-ink-50/90 via-leaf-50/60 to-ink-100/40 dark:from-ink-950/90 dark:via-leaf-950/70 dark:to-ink-900/90 border-ink-200/80 dark:border-ink-700/60',
            self::MentalEmotionalWellness => 'from-ink-50/95 via-ember-50/40 to-ink-100/60 dark:from-ink-950/95 dark:via-ember-950/40 dark:to-charcoal-950/90 border-ink-300/60 dark:border-ink-800/60',
            self::PhysicalHealthVitality => 'from-ember-50/80 via-leaf-50/50 to-ember-100/40 dark:from-ember-950/70 dark:via-leaf-950/50 dark:to-charcoal-950/90 border-ember-200/80 dark:border-ember-800/60',
            self::HealingRecovery => 'from-leaf-50/95 via-leaf-100/60 to-ink-50/50 dark:from-leaf-950/95 dark:via-leaf-900/70 dark:to-ink-950/90 border-leaf-300/70 dark:border-leaf-700/60',
            self::SelfCareBalance => 'from-ink-50/80 via-leaf-50/70 to-ember-50/30 dark:from-ink-950/80 dark:via-leaf-950/60 dark:to-ember-950/40 border-ink-200/70 dark:border-ink-800/60',
            self::ResilienceMotivation => 'from-ember-50/90 via-ink-50/60 to-ember-100/50 dark:from-ember-950/80 dark:via-ink-950/70 dark:to-charcoal-950/90 border-ember-300/70 dark:border-ember-700/60',
            self::CompassionConnection => 'from-ember-50/85 via-leaf-50/60 to-ink-50/50 dark:from-ember-950/75 dark:via-leaf-950/60 dark:to-ink-950/90 border-ember-200/70 dark:border-ember-800/60',
            self::SpiritualReflection => 'from-leaf-50/85 via-ink-50/80 to-leaf-100/60 dark:from-leaf-950/90 dark:via-ink-950/85 dark:to-leaf-900/80 border-leaf-200/70 dark:border-leaf-800/60',
        };
    }

    /**
     * Accent color for decorative quote marks and category icon.
     */
    public function accentColorClass(): string
    {
        return match ($this) {
            self::RestRelaxation => 'text-leaf-600 dark:text-leaf-400',
            self::MindfulnessPresence => 'text-ink-600 dark:text-ink-300',
            self::MentalEmotionalWellness => 'text-ink-700 dark:text-ink-300',
            self::PhysicalHealthVitality => 'text-ember-600 dark:text-ember-400',
            self::HealingRecovery => 'text-leaf-700 dark:text-leaf-300',
            self::SelfCareBalance => 'text-leaf-600 dark:text-leaf-400',
            self::ResilienceMotivation => 'text-ember-600 dark:text-ember-400',
            self::CompassionConnection => 'text-ember-500 dark:text-ember-400',
            self::SpiritualReflection => 'text-leaf-600 dark:text-leaf-300',
        };
    }

    /**
     * Category badge pill styling.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::RestRelaxation => 'bg-leaf-100 text-leaf-800 dark:bg-leaf-900/60 dark:text-leaf-200',
            self::MindfulnessPresence => 'bg-ink-100 text-ink-800 dark:bg-ink-800/60 dark:text-ink-200',
            self::MentalEmotionalWellness => 'bg-ink-100 text-ink-900 dark:bg-ink-900/80 dark:text-ink-100',
            self::PhysicalHealthVitality => 'bg-ember-100 text-ember-900 dark:bg-ember-950/70 dark:text-ember-200',
            self::HealingRecovery => 'bg-leaf-100 text-leaf-900 dark:bg-leaf-900/80 dark:text-leaf-100',
            self::SelfCareBalance => 'bg-ink-100 text-ink-800 dark:bg-ink-900/60 dark:text-ink-200',
            self::ResilienceMotivation => 'bg-ember-100 text-ember-800 dark:bg-ember-900/60 dark:text-ember-200',
            self::CompassionConnection => 'bg-ember-100 text-ember-900 dark:bg-ember-950/60 dark:text-ember-200',
            self::SpiritualReflection => 'bg-leaf-100 text-leaf-800 dark:bg-leaf-950/60 dark:text-leaf-200',
        };
    }
}
