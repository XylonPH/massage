<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\RecordLifecycleStatus;
use App\Models\Contribution;
use App\Models\Establishment;
use App\Rules\PublicContactUrl;
use App\Support\Taxonomy\TaxonomyOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EstablishmentForm extends Component
{
    public ?string $establishment = null;

    /** Whether this instance was reached through the member contribution route rather than editorial direct-edit. */
    public bool $isContribution = false;

    public string $type_establishment_relationship = 'NON';

    public bool $is_workspace_access_requested = false;

    public ?string $relationship_note = null;

    /** 1 = who-you-are, 2 = spa details tabs, 3 = review and submit. Editorial mode never advances past 1 (no wizard chrome shown). */
    public int $currentStep = 1;

    public ?string $submission_note = null;

    public bool $date_opened_is_approximate = false;

    public bool $date_closed_is_approximate = false;

    /** @var array<string, mixed> */
    public array $state = [];

    /** Declared relationship options for the contribution flow; mirrors the retired simple contribution form. */
    private const RELATIONSHIP_TYPES = ['NON', 'OWN', 'INV', 'MGR', 'OPR', 'REP'];

    /** Translated field -> flat English state key. */
    private const TRANSLATED_FIELDS = [
        'display_name' => 'display_name_eng',
        'short_description' => 'short_description_eng',
        'description' => 'description_eng',
        'direction_note' => 'direction_note_eng',
        'parking_note' => 'parking_note_eng',
    ];

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
        'date_opened', 'date_opened_precision', 'date_opened_qualifier',
        'date_closed', 'date_closed_precision', 'date_closed_qualifier',
    ];

    /** Multi-select array fields. */
    private const LIST_FIELDS = [
        'mode_service_delivery', 'target_client_focus', 'amenity_list',
        'room_types', 'bed_mat_chair_setup', 'accessibility_feature_list',
        'parking_availability_list',
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
        $this->isContribution = request()->routeIs('workspace.contribution.establishment.create');

        $record = $establishment !== null ? Establishment::query()->findOrFail($establishment) : null;

        $this->state = [];
        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
            $this->state[$stateKey] = $record?->getAttribute($field)['eng'] ?? '';
        }

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

    public function nextStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->validate($this->rulesForStep($this->currentStep));
        $this->currentStep = min(3, $this->currentStep + 1);
    }

    public function prevStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->currentStep = max(1, $this->currentStep - 1);
    }

    /** @return array<string, mixed> */
    private function rulesForStep(int $step): array
    {
        $rules = $this->rules();

        return match ($step) {
            1 => array_intersect_key($rules, array_flip(['type_establishment_relationship', 'is_workspace_access_requested', 'relationship_note'])),
            default => $rules,
        };
    }

    /** @return array<string, mixed> */
    protected function rules(): array
    {
        $rules = [
            'state.display_name_eng' => ['required', 'string', 'max:255'],
            'state.short_description_eng' => ['nullable', 'string'],
            'state.description_eng' => ['nullable', 'string'],
            'state.email' => ['nullable', 'email', 'max:255'],
            'state.contact_number' => ['nullable', 'string', 'max:255'],
            'state.type_spa' => ['required', 'string'],
            'state.status_establishment' => ['required', 'string'],
            'state.status_record_lifecycle' => ['required', 'string'],
            'state.date_opened' => ['nullable', 'date'],
            'state.date_opened_precision' => ['nullable', 'string', Rule::in(['D', 'M', 'Y', 'U'])],
            'state.date_opened_qualifier' => ['nullable', 'string', Rule::in(['EXA', 'APP', 'BFR', 'AFT', 'RNG', 'OPS', 'OPE'])],
            'state.date_closed' => [
                Rule::requiredIf(in_array($this->state['status_establishment'] ?? null, ['TC', 'PC', 'RL'], true)),
                'nullable', 'date', 'after_or_equal:state.date_opened',
            ],
            'state.date_closed_precision' => ['nullable', 'string', Rule::in(['D', 'M', 'Y', 'U'])],
            'state.date_closed_qualifier' => ['nullable', 'string', Rule::in(['EXA', 'APP', 'BFR', 'AFT', 'RNG', 'OPS', 'OPE'])],
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

        if ($this->isContribution) {
            $rules['type_establishment_relationship'] = ['required', Rule::in(self::RELATIONSHIP_TYPES)];
            $rules['is_workspace_access_requested'] = ['nullable', 'boolean'];
            $rules['relationship_note'] = ['nullable', 'string', 'max:1000'];
        }

        return $rules;
    }

    public function save(): void
    {
        $this->state['date_opened_qualifier'] = $this->date_opened_is_approximate ? 'APP' : 'EXA';
        $this->state['date_closed_qualifier'] = $this->date_closed_is_approximate ? 'APP' : 'EXA';

        $this->validate();

        if ($this->isContribution) {
            $this->submitContribution();

            return;
        }

        $record = $this->establishment !== null
            ? Establishment::query()->findOrFail($this->establishment)
            : new Establishment;

        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
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

    private function submitContribution(): void
    {
        if ($this->is_workspace_access_requested && $this->type_establishment_relationship === 'NON') {
            $this->addError('is_workspace_access_requested', __('workspace.contribution_access_relationship_required'));

            return;
        }

        $rateLimitKey = 'contribution-establishment:'.auth()->id();
        if (RateLimiter::tooManyAttempts($rateLimitKey, 10)) {
            $this->addError('form', __('workspace.contribution_rate_limited'));

            return;
        }
        RateLimiter::hit($rateLimitKey, 60);

        $proposedData = [];
        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
            $proposedData[$field] = ['eng' => $this->state[$stateKey] ?: null];
        }
        foreach (self::PLAIN_FIELDS as $field) {
            $proposedData[$field] = $this->state[$field] === '' ? null : $this->state[$field];
        }
        foreach (self::LIST_FIELDS as $field) {
            $proposedData[$field] = array_values($this->state[$field] ?? []);
        }
        foreach (array_keys(self::REPEATERS) as $field) {
            $proposedData[$field] = array_values($this->state[$field] ?? []);
        }

        Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'target_record_id' => null,
            'submitted_by_user_id' => (string) auth()->id(),
            'proposed_data' => $proposedData,
            'type_establishment_relationship' => $this->type_establishment_relationship,
            'is_workspace_access_requested' => $this->is_workspace_access_requested,
            'relationship_note' => filled($this->relationship_note) ? trim($this->relationship_note) : null,
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        session()->flash('status', __('workspace.contribution_submitted'));
        $this->redirectRoute('workspace.contribution.index', navigate: true);
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
            'air_conditioning_information', 'amenity_list', 'accessibility_feature_list',
            'parking_availability',
        ] as $field) {
            $taxonomy[$field] = TaxonomyOptions::for($field);
        }

        return view('livewire.workspace.editorial.establishment-form', [
            'taxonomy' => $taxonomy,
            'lifecycleOptions' => collect(RecordLifecycleStatus::cases())->mapWithKeys(fn ($c) => [$c->value => $c->getLabel()])->all(),
            'dayOfWeekOptions' => collect(self::DAYS_OF_WEEK)->mapWithKeys(fn ($d) => [$d => $d])->all(),
            'relationshipOptions' => $this->isContribution
                ? collect(self::RELATIONSHIP_TYPES)->mapWithKeys(fn ($t) => [$t => __('workspace.establishment_relationship_'.$t)])->all()
                : [],
        ])
            ->layout('layouts.workspace', ['navActive' => $this->isContribution ? 'contributions' : 'admin-editorial'])
            ->title($this->isContribution ? __('workspace.contribution_establishment_title') : __('editorial.establishments'));
    }
}
