<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Service;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ServiceIndex extends Component
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
        Service::query()->where('_id', $id)->delete();
        session()->flash('editorial_status', __('editorial.deleted'));
    }

    public function render(): View
    {
        $query = Service::query()->orderBy('updated_at', 'desc');

        if ($this->search !== '') {
            $query->where('service_name.eng', 'like', '%'.$this->search.'%');
        }

        return view('livewire.workspace.editorial.service-index', [
            'services' => $query->paginate(15),
        ])->title(__('editorial.services'));
    }
}
