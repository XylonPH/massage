<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Quote;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class QuoteIndex extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function deleteRecord(string $id): void
    {
        Quote::query()->where('_id', $id)->delete();
        session()->flash('editorial_status', __('editorial.deleted'));
    }

    public function render(): View
    {
        $query = Quote::query()->orderBy('updated_at', 'desc');

        if ($this->search !== '') {
            $query->where('quote_text.eng.text', 'like', '%'.$this->search.'%');
        }

        return view('livewire.workspace.editorial.quote-index', [
            'quotes' => $query->paginate(15),
        ])->title(__('editorial.quotes'));
    }
}
