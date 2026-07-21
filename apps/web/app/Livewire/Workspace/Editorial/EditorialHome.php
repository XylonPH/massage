<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Establishment;
use App\Models\Quote;
use App\Models\Service;
use App\Support\Article\PendingArticleRevisions;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EditorialHome extends Component
{
    public function render(): View
    {
        return view('livewire.workspace.editorial.editorial-home', [
            'establishmentCount' => Establishment::query()->count(),
            'serviceCount' => Service::query()->count(),
            'quoteCount' => Quote::query()->count(),
            'articleCount' => PendingArticleRevisions::all()->count(),
        ])->title(__('editorial.title'));
    }
}
