<?php

namespace App\Livewire\Content;

use Livewire\Component;
use Livewire\Attributes\Layout;

class ComicReader extends Component
{
    #[Layout('components.layouts.app')]
    public function render()
    {
        return view('livewire.content.comic-reader', [
            'comicTitle' => 'Episode 1: The New Guy',
            'comicDate' => '2026-07-24',
        ]);
    }
}
