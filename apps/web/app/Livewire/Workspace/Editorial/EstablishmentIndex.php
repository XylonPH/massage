<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Establishment;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EstablishmentIndex extends Component
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
        Establishment::query()->where('_id', $id)->delete();
        session()->flash('editorial_status', __('editorial.deleted'));
    }

    public function render(): View
    {
        $query = Establishment::query()->orderBy('updated_at', 'desc');

        if ($this->search !== '') {
            $query->where('display_name.eng', 'like', '%'.$this->search.'%');
        }

        return view('livewire.workspace.editorial.establishment-index', [
            'establishments' => $query->paginate(15),
        ])->title(__('editorial.establishments'));
    }
}
