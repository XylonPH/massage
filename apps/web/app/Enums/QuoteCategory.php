<?php

namespace App\Enums;

enum QuoteCategory: string
{
    case Wellness = 'WEL';
    case Relaxation = 'RLX';
    case Massage = 'MSG';
    case SelfCare = 'SLF';
    case Motivational = 'MOT';

    public function getLabel(): string
    {
        return match ($this) {
            self::Wellness => 'Wellness',
            self::Relaxation => 'Relaxation',
            self::Massage => 'Massage',
            self::SelfCare => 'Self-Care',
            self::Motivational => 'Motivational',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Wellness => 'General wellness, health, and balanced-living quotes.',
            self::Relaxation => 'Calm, rest, and relaxation quotes.',
            self::Massage => 'Quotes about massage, touch, and bodywork.',
            self::SelfCare => 'Self-care and personal-restoration quotes.',
            self::Motivational => 'Motivational and encouraging quotes.',
        };
    }

    public function iconName(): string
    {
        return match ($this) {
            self::Wellness => 'sparkles',
            self::Relaxation => 'moon',
            self::Massage => 'hands-holding',
            self::SelfCare => 'scale',
            self::Motivational => 'sun-rising',
        };
    }

    /**
     * Light and dark mode background styling for Quote of the Day panel.
     */
    public function panelGradientClass(): string
    {
        return match ($this) {
            self::Wellness => 'from-leaf-50/90 via-ink-50/70 to-leaf-100/50 dark:from-leaf-950/90 dark:via-ink-950/80 dark:to-charcoal-950/90 border-leaf-200/80 dark:border-leaf-800/60',
            self::Relaxation => 'from-ink-50/90 via-leaf-50/60 to-ink-100/40 dark:from-ink-950/90 dark:via-leaf-950/70 dark:to-ink-900/90 border-ink-200/80 dark:border-ink-700/60',
            self::Massage => 'from-leaf-50/95 via-leaf-100/60 to-ink-50/50 dark:from-leaf-950/95 dark:via-leaf-900/70 dark:to-ink-950/90 border-leaf-300/70 dark:border-leaf-700/60',
            self::SelfCare => 'from-ink-50/80 via-leaf-50/70 to-ember-50/30 dark:from-ink-950/80 dark:via-leaf-950/60 dark:to-ember-950/40 border-ink-200/70 dark:border-ink-800/60',
            self::Motivational => 'from-ember-50/90 via-ink-50/60 to-ember-100/50 dark:from-ember-950/80 dark:via-ink-950/70 dark:to-charcoal-950/90 border-ember-300/70 dark:border-ember-700/60',
        };
    }

    /**
     * Accent color for decorative quote marks and category icon.
     */
    public function accentColorClass(): string
    {
        return match ($this) {
            self::Wellness => 'text-leaf-600 dark:text-leaf-400',
            self::Relaxation => 'text-ink-600 dark:text-ink-300',
            self::Massage => 'text-leaf-700 dark:text-leaf-300',
            self::SelfCare => 'text-leaf-600 dark:text-leaf-400',
            self::Motivational => 'text-ember-600 dark:text-ember-400',
        };
    }

    /**
     * Category badge pill styling.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Wellness => 'bg-leaf-100 text-leaf-800 dark:bg-leaf-900/60 dark:text-leaf-200',
            self::Relaxation => 'bg-ink-100 text-ink-800 dark:bg-ink-800/60 dark:text-ink-200',
            self::Massage => 'bg-leaf-100 text-leaf-900 dark:bg-leaf-900/80 dark:text-leaf-100',
            self::SelfCare => 'bg-ink-100 text-ink-800 dark:bg-ink-900/60 dark:text-ink-200',
            self::Motivational => 'bg-ember-100 text-ember-800 dark:bg-ember-900/60 dark:text-ember-200',
        };
    }
}
