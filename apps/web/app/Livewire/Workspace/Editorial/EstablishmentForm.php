<?php

namespace App\Livewire\Workspace\Editorial;

use App\Models\Establishment;
use App\Rules\PublicContactUrl;
use App\Support\Taxonomy\TaxonomyOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.workspace', ['navActive' => 'admin-editorial'])]
class EstablishmentForm extends Component
{
    public ?string $establishment = null;

    /** @var array<string, mixed> */
    public array $state = [];

    /** Scalar/plain fields copied verbatim between $state and the model. */
    private const PLAIN_FIELDS = [
        'email', 'contact_number', 'address_public', 'coordinate_latitude',
        'coordinate_longitude', 'type_spa', 'level_spa_market',
        'type_physical_setting', 'type_establishment_operation',
        'status_establishment', 'mode_access', 'type_client_access',
        'status_record_lifecycle', 'shower_availability', 'sauna_availability',
        'steam_room_availability', 'jacuzzi_availability', 'locker_availability',
        'couple_room_availability', 'private_room_availability',
        'curtain_divider_information', 'air_conditioning_information',
    ];

    /** Multi-select array fields. */
    private const LIST_FIELDS = [
        'mode_service_delivery', 'target_client_focus', 'amenities',
        'room_types', 'bed_mat_chair_setup', 'accessibility_information',
    ];

    /** Repeater fields with their blank-row shapes. */
    private const REPEATERS = [
        'landmark_list' => ['landmark_name' => '', 'walking_duration_minute' => null],
        'contact_channel_list' => ['type_contact_channel' => '', 'type_contact_number' => '', 'contact_label' => '', 'contact_value' => '', 'contact_url' => '', 'status_contact_channel' => ''],
        'treatment_area_list' => ['treatment_area_name' => '', 'type_treatment_area' => '', 'level_treatment_privacy' => '', 'type_treatment_capacity' => '', 'treatment_area_note' => ''],
        'operating_hours' => ['day_of_week' => '', 'open_time' => null, 'close_time' => null],
    ];

    /** Fixed value list for the operating_hours day_of_week select — not taxonomy-driven, mirrors the Filament source form verbatim. */
    private const DAYS_OF_WEEK = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Public Holidays'];

    public function mount(?string $establishment = null): void
    {
        $this->establishment = $establishment;

        $record = $establishment !== null ? Establishment::query()->findOrFail($establishment) : null;

        $this->state = [
            'display_name_eng' => $record?->display_name['eng'] ?? '',
            'short_description_eng' => $record?->short_description['eng'] ?? '',
            'description_eng' => $record?->description['eng'] ?? '',
            'direction_note_eng' => $record?->direction_note['eng'] ?? '',
            'parking_note_eng' => $record?->parking_note['eng'] ?? '',
        ];

        foreach (self::PLAIN_FIELDS as $field) {
            $this->state[$field] = $record?->getAttribute($field) ?? ($field === 'status_record_lifecycle' ? 'ACT' : '');
        }

        foreach (self::LIST_FIELDS as $field) {
            $this->state[$field] = $record?->getAttribute($field) ?? [];
        }

        foreach (self::REPEATERS as $field => $blank) {
            $this->state[$field] = $record?->getAttribute($field) ?? [];
        }

        // Filament's source form defaulted new establishments to 7 blank operating_hours rows
        // (Monday–Sunday); replicate that so the Livewire form doesn't regress the authoring experience.
        if ($record === null) {
            foreach (array_slice(self::DAYS_OF_WEEK, 0, 7) as $day) {
                $this->state['operating_hours'][] = ['day_of_week' => $day, 'open_time' => null, 'close_time' => null];
            }
        }
    }

    public function addRow(string $repeater): void
    {
        abort_unless(array_key_exists($repeater, self::REPEATERS), 400);
        $this->state[$repeater][] = self::REPEATERS[$repeater];
    }

    public function removeRow(string $repeater, int $index): void
    {
        abort_unless(array_key_exists($repeater, self::REPEATERS), 400);
        unset($this->state[$repeater][$index]);
        $this->state[$repeater] = array_values($this->state[$repeater]);
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        return [
            'state.display_name_eng' => ['required', 'string', 'max:255'],
            'state.short_description_eng' => ['nullable', 'string'],
            'state.description_eng' => ['nullable', 'string'],
            'state.email' => ['nullable', 'email', 'max:255'],
            'state.contact_number' => ['nullable', 'string', 'max:255'],
            'state.type_spa' => ['required', 'string'],
            'state.status_establishment' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
            'state.coordinate_latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'state.coordinate_longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'state.landmark_list.*.landmark_name' => ['required', 'string', 'max:255'],
            'state.landmark_list.*.walking_duration_minute' => ['nullable', 'numeric', 'min:0'],
            'state.contact_channel_list.*.type_contact_channel' => ['required', 'string'],
            'state.contact_channel_list.*.contact_label' => ['required', 'string', 'max:100'],
            'state.contact_channel_list.*.contact_value' => ['required', 'string', 'max:255'],
            'state.contact_channel_list.*.contact_url' => ['required', 'string', 'max:2048', new PublicContactUrl],
            'state.treatment_area_list.*.treatment_area_name' => ['required', 'string', 'max:255'],
            'state.treatment_area_list.*.treatment_area_note' => ['nullable', 'string', 'max:255'],
            'state.operating_hours.*.day_of_week' => ['required', 'string', Rule::in(self::DAYS_OF_WEEK)],
            'state.operating_hours.*.open_time' => ['nullable', 'date_format:H:i'],
            'state.operating_hours.*.close_time' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        $record = $this->establishment !== null
            ? Establishment::query()->findOrFail($this->establishment)
            : new Establishment;

        foreach (['display_name' => 'display_name_eng', 'short_description' => 'short_description_eng', 'description' => 'description_eng', 'direction_note' => 'direction_note_eng', 'parking_note' => 'parking_note_eng'] as $field => $stateKey) {
            $value = $record->getAttribute($field) ?? [];
            $value['eng'] = $this->state[$stateKey] ?: null;
            $record->setAttribute($field, $value);
        }

        $plain = [];
        foreach (self::PLAIN_FIELDS as $field) {
            $plain[$field] = $this->state[$field] === '' ? null : $this->state[$field];
        }
        foreach (self::LIST_FIELDS as $field) {
            $plain[$field] = array_values($this->state[$field] ?? []);
        }
        foreach (array_keys(self::REPEATERS) as $field) {
            $plain[$field] = array_values($this->state[$field] ?? []);
        }
        $record->fill($plain);
        $record->save();

        session()->flash('editorial_status', $this->establishment !== null ? __('editorial.updated') : __('editorial.created'));
        $this->redirectRoute('workspace.editorial.establishment.index', navigate: true);
    }

    public function render(): View
    {
        $taxonomy = [];
        foreach ([
            'type_spa', 'level_spa_market', 'type_physical_setting',
            'type_establishment_operation', 'status_establishment',
            'mode_service_delivery', 'mode_access', 'type_client_access',
            'target_client_focus', 'type_contact_channel', 'type_contact_number',
            'status_contact_channel', 'type_treatment_area',
            'level_treatment_privacy', 'type_treatment_capacity', 'room_types',
            'bed_mat_chair_setup', 'shower_availability', 'sauna_availability',
            'steam_room_availability', 'jacuzzi_availability',
            'locker_availability', 'couple_room_availability',
            'private_room_availability', 'curtain_divider_information',
            'air_conditioning_information', 'amenities', 'accessibility_information',
        ] as $field) {
            $taxonomy[$field] = TaxonomyOptions::for($field);
        }

        return view('livewire.workspace.editorial.establishment-form', [
            'taxonomy' => $taxonomy,
            'lifecycleOptions' => collect(\App\Enums\RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
            'dayOfWeekOptions' => collect(self::DAYS_OF_WEEK)->mapWithKeys(fn ($d) => [$d => $d])->all(),
        ])->title(__('editorial.establishments'));
    }
}
