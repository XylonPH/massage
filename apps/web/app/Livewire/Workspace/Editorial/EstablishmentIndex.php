<?php

namespace App\Livewire\Workspace\Editorial;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EstablishmentIndex extends Component
{
    public function render(): View
    {
        return view('livewire.workspace.editorial.establishment-index')
            ->title(__('editorial.establishments'));
    }
}
