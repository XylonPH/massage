<?php

namespace App\View\Components;

use App\Enums\QuoteCategory;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class QuotePanel extends Component
{
    public ?QuoteCategory $category;

    public string $gradientClass;

    public string $accentClass;

    public string $badgeClass;

    /**
     * @param  array<string, mixed>|null  $quote
     */
    public function __construct(public ?array $quote = null)
    {
        $this->category = $quote['category'] ?? null;
        if (is_string($this->category)) {
            $this->category = QuoteCategory::tryFrom($this->category);
        }
        $this->category ??= QuoteCategory::Wellness;

        $this->gradientClass = $this->category->panelGradientClass();
        $this->accentClass = $this->category->accentColorClass();
        $this->badgeClass = $this->category->badgeClass();
    }

    public function render(): View
    {
        return view('components.quote-panel');
    }
}
