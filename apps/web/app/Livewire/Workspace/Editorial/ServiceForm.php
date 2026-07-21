<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\RecordLifecycleStatus;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class ServiceForm extends Component
{
    public ?string $service = null;

    /** @var array<string, mixed> */
    public array $state = [
        'english_name' => '',
        'english_short_description' => '',
        'english_overview' => '',
        'chinese_name' => '',
        'chinese_short_description' => '',
        'chinese_overview' => '',
        'service_slug' => '',
        'group_service_sector' => '',
        'group_service_domain' => '',
        'group_service_family' => '',
        'status_record_lifecycle' => 'ACT',
    ];

    public function mount(?string $service = null): void
    {
        $this->service = $service;

        if ($service !== null) {
            $record = Service::query()->findOrFail($service);
            $this->state = [
                'english_name' => $record->english_name ?? '',
                'english_short_description' => $record->english_short_description ?? '',
                'english_overview' => $record->english_overview ?? '',
                'chinese_name' => $record->chinese_name ?? '',
                'chinese_short_description' => $record->chinese_short_description ?? '',
                'chinese_overview' => $record->chinese_overview ?? '',
                'service_slug' => $record->service_slug ?? '',
                'group_service_sector' => $record->group_service_sector ?? '',
                'group_service_domain' => $record->group_service_domain ?? '',
                'group_service_family' => $record->group_service_family ?? '',
                'status_record_lifecycle' => $record->status_record_lifecycle?->value ?? 'ACT',
            ];
        }
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.english_name' => ['required', 'string', 'max:150'],
            'state.english_short_description' => ['nullable', 'string', 'max:300'],
            'state.english_overview' => ['nullable', 'string', 'max:2000'],
            'state.chinese_name' => ['nullable', 'string', 'max:150'],
            'state.chinese_short_description' => ['nullable', 'string', 'max:300'],
            'state.chinese_overview' => ['nullable', 'string', 'max:2000'],
            'state.service_slug' => ['required', 'string', 'max:100'],
            'state.group_service_sector' => ['nullable', 'string', 'max:100'],
            'state.group_service_domain' => ['nullable', 'string', 'max:100'],
            'state.group_service_family' => ['required', 'string', 'max:100'],
            'state.status_record_lifecycle' => ['required', 'string'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $slugTaken = Service::query()
            ->where('service_slug', $this->state['service_slug'])
            ->when($this->service !== null, fn ($q) => $q->where('_id', '!=', $this->service))
            ->exists();

        if ($slugTaken) {
            $this->addError('state.service_slug', __('editorial.slug_taken'));

            return;
        }

        $record = $this->service !== null
            ? Service::query()->findOrFail($this->service)
            : new Service;

        $record->english_name = $this->state['english_name'];
        $record->english_short_description = $this->state['english_short_description'];
        $record->english_overview = $this->state['english_overview'];

        if ($this->state['chinese_name'] !== '') {
            $record->chinese_name = $this->state['chinese_name'];
        }
        if ($this->state['chinese_short_description'] !== '') {
            $record->chinese_short_description = $this->state['chinese_short_description'];
        }
        if ($this->state['chinese_overview'] !== '') {
            $record->chinese_overview = $this->state['chinese_overview'];
        }

        $record->fill([
            'service_slug' => $this->state['service_slug'],
            'group_service_sector' => $this->state['group_service_sector'] ?: null,
            'group_service_domain' => $this->state['group_service_domain'] ?: null,
            'group_service_family' => $this->state['group_service_family'],
            'status_record_lifecycle' => $this->state['status_record_lifecycle'],
        ]);
        $record->save();

        session()->flash('editorial_status', $this->service !== null ? __('editorial.updated') : __('editorial.created'));
        $this->redirectRoute('workspace.editorial.service.index', navigate: true);
    }

    public function render(): View
    {
        return view('livewire.workspace.editorial.service-form', [
            'lifecycleOptions' => collect(RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
        ])->title(__('editorial.services'));
    }
}
