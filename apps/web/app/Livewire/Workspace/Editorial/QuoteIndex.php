<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\QuoteCategory;
use App\Enums\RecordLifecycleStatus;
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

    #[Url]
    public string $category = '';

    #[Url]
    public string $lifecycle = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedLifecycle(): void
    {
        $this->resetPage();
    }

    public function retireRecord(string $id): void
    {
        Quote::query()->where('_id', $id)->update(['status_record_lifecycle' => RecordLifecycleStatus::Inactive->value]);
        session()->flash('editorial_status', __('editorial.updated'));
    }

    public function restoreRecord(string $id): void
    {
        Quote::query()->where('_id', $id)->update(['status_record_lifecycle' => RecordLifecycleStatus::Active->value]);
        session()->flash('editorial_status', __('editorial.updated'));
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
            $s = trim($this->search);
            $query->where(function ($q) use ($s) {
                $q->where('attribution_label', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.eng.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.fil.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.ceb.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.kor.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.spa.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.zho-hans.text', 'like', '%'.$s.'%')
                    ->orWhere('quote_text.zho-hant.text', 'like', '%'.$s.'%');
            });
        }

        if ($this->category !== '') {
            $query->where('type_quote_category', $this->category);
        }

        if ($this->lifecycle !== '') {
            $query->where('status_record_lifecycle', $this->lifecycle);
        }

        return view('livewire.workspace.editorial.quote-index', [
            'quotes' => $query->paginate(15),
            'categories' => QuoteCategory::cases(),
            'lifecycleStatuses' => RecordLifecycleStatus::cases(),
        ])->title(__('editorial.quotes'));
    }
}
