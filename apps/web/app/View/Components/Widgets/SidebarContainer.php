<?php

namespace App\View\Components\Widgets;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SidebarContainer extends Component
{
    public array $widgets = [];

    /**
     * Create a new component instance.
     */
    public function __construct(public int $contentLength = 0)
    {
        // Simple heuristic: 1 widget for short, 2 for medium, 3-4 for long content.
        $widgetCount = 1;
        if ($contentLength > 500) {
            $widgetCount = 2;
        }
        if ($contentLength > 1500) {
            $widgetCount = 3;
        }
        if ($contentLength > 3000) {
            $widgetCount = 4;
        }

        $availableWidgets = [
            'trending-spas',
            'trending-therapists',
            'recent-reviews',
            'recent-discussions',
            'interactive-quiz',
            'interactive-survey',
        ];

        // If user is logged in, add user-recent-visits to the pool
        if (auth()->check()) {
            $availableWidgets[] = 'user-recent-visits';
        }

        // Randomize the order of widgets and pick $widgetCount
        shuffle($availableWidgets);
        $this->widgets = array_slice($availableWidgets, 0, $widgetCount);

        // Ensure interactive-quiz and interactive-survey are prioritized sometimes, or just let shuffle do it.
        // For demonstration, shuffle is fine.
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.widgets.sidebar-container');
    }
}
