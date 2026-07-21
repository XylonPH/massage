<?php

namespace App\Livewire\Workspace\Editorial;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteIndex extends Component
{
    public function render(): View
    {
        return view('livewire.workspace.editorial.quote-index')
            ->title(__('editorial.quotes'));
    }
}
