<?php

namespace App\Livewire\Workspace\Editorial;

use App\Enums\RecordLifecycleStatus;
use App\Events\EstablishmentContributionSubmitted;
use App\Models\Contribution;
use App\Models\Establishment;
use App\Rules\PublicContactUrl;
use App\Support\Address\AddressLookup;
use App\Support\Establishment\DuplicateEstablishmentFinder;
use App\Support\Taxonomy\TaxonomyOptions;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
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

    /** @var Collection<int, array{id: string, display_name: string, address_public: ?string, source: string}> */
    public Collection $duplicateCandidates;

    public bool $duplicateAcknowledged = false;

    public bool $is_visit_requested = false;

    public ?string $visit_preferred_time_note = null;

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
        'email', 'contact_number', 'address_public',
        'official_name', 'country_id', 'region_id', 'city_name', 'street_address',
        'building_name', 'floor_label', 'unit_label', 'postal_code',
        'coordinate_latitude',
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
        'landmark_list' => ['landmark_name' => '', 'walking_duration_minute' => null, 'direction_note_eng' => ''],
        'contact_channel_list' => ['type_contact_channel' => '', 'type_contact_number' => '', 'contact_label' => '', 'contact_value' => '', 'contact_url' => '', 'status_contact_channel' => ''],
        'treatment_area_list' => ['treatment_area_name' => '', 'type_treatment_area' => '', 'level_treatment_privacy' => '', 'type_treatment_capacity' => '', 'treatment_area_note' => ''],
        'operating_hours' => ['day_of_week' => '', 'open_time' => null, 'close_time' => null, 'is_closed' => false],
    ];

    /** type_spa codes that mean the spa has no physical premises (home-service/mobile-only) unless it also delivers on-site. */
    private const HOME_SERVICE_ONLY_TYPES = ['HP', 'MB'];

    /** @var list<string> Field names to strip from a submission when hasPhysicalPremises() is false. */
    private const PHYSICAL_PREMISES_FIELDS = [
        'shower_availability', 'sauna_availability', 'steam_room_availability',
        'jacuzzi_availability', 'locker_availability', 'couple_room_availability',
        'private_room_availability', 'curtain_divider_information',
        'air_conditioning_information', 'room_types', 'bed_mat_chair_setup',
        'amenity_list', 'accessibility_feature_list',
    ];

    /** Fixed value list for the operating_hours day_of_week select — not taxonomy-driven, mirrors the Filament source form verbatim. */
    private const DAYS_OF_WEEK = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday', 'Public Holidays'];

    /** Codes from type_contact_channel that represent a phone number and therefore need the number-type subfield. Confirmed against data/taxonomy/shared/person_identity_and_contact.json. */
    private const PHONE_CHANNEL_CODES = ['PHN'];

    /**
     * PLAIN_FIELDS entries that data/structure_guide/establishment_main.php does not own,
     * so they are excluded when building proposed_data.establishment (see
     * data/structure_guide/user_contribution.php: "proposed_data follows the target
     * collection's field contracts"). email/contact_number belong to establishment_contact
     * and are already hidden from the contribution UI's identity tab; region_id/city_name
     * are Location-tab inputs already folded into address_public; status_record_lifecycle
     * is system-owned and set by editorial review, never by a contributor.
     */
    private const CONTRIBUTION_NON_ESTABLISHMENT_PLAIN_FIELDS = [
        'email', 'contact_number', 'region_id', 'city_name', 'status_record_lifecycle',
    ];

    /** Countries/regions eligible for an in-person verification visit request. */
    private const VISIT_ELIGIBLE_COUNTRY_LABEL = 'Philippines';

    private const VISIT_ELIGIBLE_REGION_LABEL = 'National Capital Region';

    public function channelNeedsPhoneType(string $channelType): bool
    {
        return in_array($channelType, self::PHONE_CHANNEL_CODES, true);
    }

    public function channelValueInputType(string $channelType): string
    {
        return match ($channelType) {
            'EML' => 'email',
            default => 'text',
        };
    }

    public function checkForDuplicates(): void
    {
        $this->duplicateCandidates = app(DuplicateEstablishmentFinder::class)
            ->find($this->state['display_name_eng'] ?? '');
    }

    public function visitEligible(): bool
    {
        $lookup = app(AddressLookup::class);
        $countryLabel = $lookup->countries()[(string) ($this->state['country_id'] ?? '')] ?? null;
        $regionLabel = filled($this->state['country_id'] ?? null)
            ? ($lookup->regions((int) $this->state['country_id'])[(string) ($this->state['region_id'] ?? '')] ?? null)
            : null;

        return $countryLabel === self::VISIT_ELIGIBLE_COUNTRY_LABEL && $regionLabel === self::VISIT_ELIGIBLE_REGION_LABEL;
    }

    public function hasPhysicalPremises(): bool
    {
        $deliversOnSite = in_array('OS', $this->state['mode_service_delivery'] ?? [], true);
        $homeServiceOnlyType = in_array($this->state['type_spa'] ?? '', self::HOME_SERVICE_ONLY_TYPES, true);

        return $deliversOnSite || ! $homeServiceOnlyType;
    }

    public function updatedState(mixed $value, string $key): void
    {
        if (preg_match('/^operating_hours\.(\d+)\.is_closed$/', $key, $matches) && $value) {
            $this->state['operating_hours'][(int) $matches[1]]['open_time'] = null;
            $this->state['operating_hours'][(int) $matches[1]]['close_time'] = null;
        }
    }

    public function mount(?string $establishment = null): void
    {
        $this->duplicateCandidates = collect();

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

        $this->date_opened_is_approximate = $this->state['date_opened_qualifier'] === 'APP';
        $this->date_closed_is_approximate = $this->state['date_closed_qualifier'] === 'APP';

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
                $this->state['operating_hours'][] = ['day_of_week' => $day, 'open_time' => null, 'close_time' => null, 'is_closed' => false];
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

    public function composeAddressPublic(): void
    {
        $lookup = app(AddressLookup::class);
        $parts = array_filter([
            $this->state['street_address'] ?? null,
            $this->state['city_name'] ?? null,
            filled($this->state['region_id'] ?? null) ? ($lookup->regions((int) $this->state['country_id'])[(string) $this->state['region_id']] ?? null) : null,
            filled($this->state['country_id'] ?? null) ? ($lookup->countries()[(string) $this->state['country_id']] ?? null) : null,
        ]);

        $this->state['address_public'] = implode(', ', $parts);
    }

    public function updatedStateCountryId(): void
    {
        $this->state['region_id'] = '';
        $this->state['city_name'] = '';
    }

    /** @return array<string, string> */
    public function citiesForSelectedRegion(): array
    {
        return filled($this->state['region_id'] ?? null)
            ? app(AddressLookup::class)->cities((int) $this->state['region_id'])
            : [];
    }

    public function nextStep(): void
    {
        if (! $this->isContribution) {
            return;
        }

        $this->validate($this->rulesForStep($this->currentStep));
        $this->currentStep = min(3, $this->currentStep + 1);

        if ($this->currentStep === 3) {
            $this->checkForDuplicates();
        }
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
            'state.official_name' => ['nullable', 'string', 'max:255'],
            'state.country_id' => ['nullable', 'integer'],
            'state.region_id' => ['nullable', 'integer'],
            'state.city_name' => ['nullable', 'string', 'max:255'],
            'state.street_address' => ['nullable', 'string', 'max:255'],
            'state.building_name' => ['nullable', 'string', 'max:255'],
            'state.floor_label' => ['nullable', 'string', 'max:50'],
            'state.unit_label' => ['nullable', 'string', 'max:50'],
            'state.postal_code' => ['nullable', 'string', 'max:20'],
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
            'state.contact_channel_list.*.type_contact_channel' => ['required', 'string', Rule::in(array_keys(TaxonomyOptions::for('type_contact_channel')))],
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
            $rules['submission_note'] = ['nullable', 'string', 'max:2000'];
            $rules['visit_preferred_time_note'] = ['nullable', 'string', 'max:500', 'required_if:is_visit_requested,true'];
            $rules['duplicateAcknowledged'] = $this->duplicateCandidates->isNotEmpty()
                ? ['accepted']
                : ['nullable', 'boolean'];
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

        if (! $this->hasPhysicalPremises()) {
            foreach (self::PHYSICAL_PREMISES_FIELDS as $field) {
                $this->state[$field] = in_array($field, self::LIST_FIELDS, true) ? [] : null;
            }
            $this->state['treatment_area_list'] = [];
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

        if (! $this->hasPhysicalPremises()) {
            foreach (self::PHYSICAL_PREMISES_FIELDS as $field) {
                $this->state[$field] = in_array($field, self::LIST_FIELDS, true) ? [] : null;
            }
            $this->state['treatment_area_list'] = [];
        }

        $establishment = [];
        foreach (self::TRANSLATED_FIELDS as $field => $stateKey) {
            // establishment_main's structure guide names this field full_description;
            // the shared TRANSLATED_FIELDS key stays 'description' because that is the
            // literal Establishment model field used by the direct-edit save() path.
            $outputField = $field === 'description' ? 'full_description' : $field;
            $establishment[$outputField] = ['eng' => $this->state[$stateKey] ?: null];
        }
        foreach (self::PLAIN_FIELDS as $field) {
            if (in_array($field, self::CONTRIBUTION_NON_ESTABLISHMENT_PLAIN_FIELDS, true)) {
                continue;
            }
            if (in_array($field, ['coordinate_latitude', 'coordinate_longitude'], true)) {
                continue;
            }
            $establishment[$field] = $this->state[$field] === '' ? null : $this->state[$field];
        }
        if (is_numeric($this->state['coordinate_latitude'] ?? null) && is_numeric($this->state['coordinate_longitude'] ?? null)) {
            $establishment['location_point'] = [
                'type' => 'Point',
                'coordinates' => [(float) $this->state['coordinate_longitude'], (float) $this->state['coordinate_latitude']],
            ];
        }
        foreach (self::LIST_FIELDS as $field) {
            $establishment[$field] = array_values($this->state[$field] ?? []);
        }
        $establishment['landmark_list'] = array_map(
            fn (array $row) => [
                'landmark_name' => $row['landmark_name'],
                'walking_duration_minute' => $row['walking_duration_minute'],
                'direction_note' => ['eng' => $row['direction_note_eng'] ?: null],
            ],
            array_values($this->state['landmark_list'] ?? []),
        );
        $establishment['treatment_area_list'] = array_values($this->state['treatment_area_list'] ?? []);

        $contactChannelList = array_values($this->state['contact_channel_list'] ?? []);
        $operatingSchedule = array_values($this->state['operating_hours'] ?? []);

        $eventList = [];
        if (filled($this->state['date_opened'] ?? null)) {
            $eventList[] = [
                'type_business_event' => 'OP',
                'effective_date' => $this->state['date_opened'],
                'type_date_precision' => $this->state['date_opened_precision'] ?: 'U',
                'type_date_qualifier' => $this->state['date_opened_qualifier'] ?: 'EXA',
            ];
        }
        if (filled($this->state['date_closed'] ?? null)) {
            $eventList[] = [
                'type_business_event' => 'CL',
                'effective_date' => $this->state['date_closed'],
                'type_date_precision' => $this->state['date_closed_precision'] ?: 'U',
                'type_date_qualifier' => $this->state['date_closed_qualifier'] ?: 'EXA',
            ];
        }

        $contribution = Contribution::query()->create([
            'type_contribution' => 'ADD',
            'target_collection' => 'establishment_main',
            'target_record_id' => null,
            'submitted_by_user_id' => (string) auth()->id(),
            'proposed_data' => [
                'establishment' => $establishment,
                'contact_channel_list' => $contactChannelList,
                'operating_schedule' => $operatingSchedule,
                'event_list' => $eventList,
            ],
            'type_establishment_relationship' => $this->type_establishment_relationship,
            'is_workspace_access_requested' => $this->is_workspace_access_requested,
            'relationship_note' => filled($this->relationship_note) ? trim($this->relationship_note) : null,
            'submission_note' => filled($this->submission_note) ? trim($this->submission_note) : null,
            'duplicate_candidate_establishment_id_list' => $this->duplicateCandidates->pluck('id')->all(),
            'duplicate_acknowledged' => $this->duplicateAcknowledged,
            'is_visit_requested' => $this->is_visit_requested,
            'visit_preferred_time_note' => filled($this->visit_preferred_time_note) ? trim($this->visit_preferred_time_note) : null,
            'status_contribution' => 'PND',
            'submitted_at' => now(),
        ]);

        event(new EstablishmentContributionSubmitted($contribution));

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
