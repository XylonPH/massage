<div class="mx-auto max-w-5xl">
    @if ($isContribution)
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ __('workspace.contribution_establishment_title') }}</h1>
        <p class="mt-2 text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_establishment_intro') }}</p>
    @else
        <h1 class="text-2xl font-black text-ink-950 dark:text-ink-50">{{ $establishment ? __('editorial.edit') : __('editorial.new') }} — {{ __('editorial.establishments') }}</h1>
    @endif

    @error('form')<p class="mt-4 text-sm text-red-700 dark:text-red-300" role="alert">{{ $message }}</p>@enderror

    <form wire:submit="save" class="mt-6 space-y-5 rounded-2xl border border-ink-100 bg-white p-6 shadow-sm dark:border-ink-800 dark:bg-ink-900">
        <div x-data="{ tab: 'identity' }">
            <x-editorial.tab-bar :tabs="array_merge([
                'identity' => __('editorial.tab_identity'),
                'classification' => __('editorial.tab_classification'),
                'access' => __('editorial.tab_access'),
                'location' => __('editorial.tab_location'),
                'contact' => __('editorial.tab_contact'),
                'hours' => __('editorial.tab_hours'),
                'facilities' => __('editorial.tab_facilities'),
                'amenities' => __('editorial.tab_amenities'),
            ], $isContribution ? ['relationship' => __('workspace.contribution_relationship_tab')] : [])" />

            {{-- Identity --}}
            <div x-show="tab === 'identity'" class="mt-5 space-y-5">
                <x-form.field :label="__('editorial.est_display_name_eng')" :error="$errors->first('state.display_name_eng')">
                    <x-form.input wire:model="state.display_name_eng" maxlength="255" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_short_description_eng')" :error="$errors->first('state.short_description_eng')">
                    <x-form.textarea wire:model="state.short_description_eng" rows="3" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_description_eng')" :error="$errors->first('state.description_eng')">
                    <x-form.textarea wire:model="state.description_eng" rows="8" />
                </x-form.field>
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.field :label="__('editorial.est_email')" :error="$errors->first('state.email')">
                        <x-form.input wire:model="state.email" type="email" maxlength="255" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_contact_number')" :error="$errors->first('state.contact_number')">
                        <x-form.input wire:model="state.contact_number" maxlength="255" />
                    </x-form.field>
                </div>
                <x-form.field :label="__('editorial.est_status_record_lifecycle')" :error="$errors->first('state.status_record_lifecycle')">
                    <x-form.select wire:model="state.status_record_lifecycle" :options="$lifecycleOptions" />
                </x-form.field>
            </div>

            {{-- Classification --}}
            <div x-show="tab === 'classification'" x-cloak class="mt-5 grid gap-5 sm:grid-cols-2">
                <x-form.field :label="__('editorial.est_type_spa')" :error="$errors->first('state.type_spa')">
                    <x-form.select wire:model="state.type_spa" :options="$taxonomy['type_spa']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_level_spa_market')" :error="$errors->first('state.level_spa_market')">
                    <x-form.select wire:model="state.level_spa_market" :options="$taxonomy['level_spa_market']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_type_physical_setting')" :error="$errors->first('state.type_physical_setting')">
                    <x-form.select wire:model="state.type_physical_setting" :options="$taxonomy['type_physical_setting']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_type_establishment_operation')" :error="$errors->first('state.type_establishment_operation')">
                    <x-form.select wire:model="state.type_establishment_operation" :options="$taxonomy['type_establishment_operation']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_status_establishment')" :error="$errors->first('state.status_establishment')">
                    <x-form.select wire:model="state.status_establishment" :options="$taxonomy['status_establishment']" :placeholder="__('editorial.select_placeholder')" />
                </x-form.field>
            </div>

            {{-- Access & delivery --}}
            <div x-show="tab === 'access'" x-cloak class="mt-5 space-y-5">
                <x-form.field :label="__('editorial.est_mode_service_delivery')">
                    <x-form.toggle-group :options="$taxonomy['mode_service_delivery']" model="state.mode_service_delivery" />
                </x-form.field>
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.field :label="__('editorial.est_mode_access')" :error="$errors->first('state.mode_access')">
                        <x-form.select wire:model="state.mode_access" :options="$taxonomy['mode_access']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_type_client_access')" :error="$errors->first('state.type_client_access')">
                        <x-form.select wire:model="state.type_client_access" :options="$taxonomy['type_client_access']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                </div>
                <x-form.field :label="__('editorial.est_target_client_focus')">
                    <x-form.toggle-group :options="$taxonomy['target_client_focus']" model="state.target_client_focus" />
                </x-form.field>
            </div>

            {{-- Location --}}
            <div x-show="tab === 'location'" x-cloak class="mt-5 space-y-5">
                <x-form.field :label="__('editorial.est_address_public')" :error="$errors->first('state.address_public')">
                    <x-form.textarea wire:model="state.address_public" rows="2" />
                </x-form.field>
                <div class="grid gap-5 sm:grid-cols-2">
                    <x-form.field :label="__('editorial.est_coordinate_latitude')" :error="$errors->first('state.coordinate_latitude')">
                        <x-form.input wire:model="state.coordinate_latitude" type="number" step="any" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_coordinate_longitude')" :error="$errors->first('state.coordinate_longitude')">
                        <x-form.input wire:model="state.coordinate_longitude" type="number" step="any" />
                    </x-form.field>
                </div>
                <x-form.field :label="__('editorial.est_direction_note_eng')" :error="$errors->first('state.direction_note_eng')">
                    <x-form.textarea wire:model="state.direction_note_eng" rows="2" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_parking_note_eng')" :error="$errors->first('state.parking_note_eng')">
                    <x-form.textarea wire:model="state.parking_note_eng" rows="2" />
                </x-form.field>

                <div class="space-y-3">
                    <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.landmarks') }}</p>
                    @foreach ($state['landmark_list'] as $i => $row)
                        <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_10rem_auto] dark:border-ink-800" wire:key="landmark-{{ $i }}">
                            <x-form.field :label="__('editorial.landmark_name')" :error="$errors->first('state.landmark_list.'.$i.'.landmark_name')">
                                <x-form.input wire:model="state.landmark_list.{{ $i }}.landmark_name" />
                            </x-form.field>
                            <x-form.field :label="__('editorial.walking_minutes')" :error="$errors->first('state.landmark_list.'.$i.'.walking_duration_minute')">
                                <x-form.input wire:model="state.landmark_list.{{ $i }}.walking_duration_minute" type="number" min="0" />
                            </x-form.field>
                            <button type="button" wire:click="removeRow('landmark_list', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addRow('landmark_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
                </div>
            </div>

            {{-- Contact --}}
            <div x-show="tab === 'contact'" x-cloak class="mt-5 space-y-3">
                <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.contact_channels') }}</p>
                @foreach ($state['contact_channel_list'] as $i => $row)
                    <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-2 lg:grid-cols-3 dark:border-ink-800" wire:key="contact-{{ $i }}">
                        <x-form.field :label="__('editorial.est_type_contact_channel')" :error="$errors->first('state.contact_channel_list.'.$i.'.type_contact_channel')">
                            <x-form.select wire:model="state.contact_channel_list.{{ $i }}.type_contact_channel" :options="$taxonomy['type_contact_channel']" :placeholder="__('editorial.select_placeholder')" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.est_type_contact_number')" :error="$errors->first('state.contact_channel_list.'.$i.'.type_contact_number')">
                            <x-form.select wire:model="state.contact_channel_list.{{ $i }}.type_contact_number" :options="$taxonomy['type_contact_number']" :placeholder="__('editorial.select_placeholder')" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.est_status_contact_channel')" :error="$errors->first('state.contact_channel_list.'.$i.'.status_contact_channel')">
                            <x-form.select wire:model="state.contact_channel_list.{{ $i }}.status_contact_channel" :options="$taxonomy['status_contact_channel']" :placeholder="__('editorial.select_placeholder')" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.est_contact_label')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_label')">
                            <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_label" maxlength="100" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.est_contact_value')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_value')">
                            <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_value" maxlength="255" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.est_contact_url')" :error="$errors->first('state.contact_channel_list.'.$i.'.contact_url')">
                            <x-form.input wire:model="state.contact_channel_list.{{ $i }}.contact_url" maxlength="2048" />
                        </x-form.field>
                        <div class="flex justify-end lg:col-span-3">
                            <button type="button" wire:click="removeRow('contact_channel_list', {{ $i }})" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                        </div>
                    </div>
                @endforeach
                <button type="button" wire:click="addRow('contact_channel_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
            </div>

            {{-- Operating hours --}}
            <div x-show="tab === 'hours'" x-cloak class="mt-5 space-y-3">
                <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.operating_hours') }}</p>
                @foreach ($state['operating_hours'] as $i => $row)
                    <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-[1fr_1fr_1fr_auto] dark:border-ink-800" wire:key="hours-{{ $i }}">
                        <x-form.field :label="__('editorial.day_of_week')" :error="$errors->first('state.operating_hours.'.$i.'.day_of_week')">
                            <x-form.select wire:model="state.operating_hours.{{ $i }}.day_of_week" :options="$dayOfWeekOptions" :placeholder="__('editorial.select_placeholder')" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.open_time')" :error="$errors->first('state.operating_hours.'.$i.'.open_time')">
                            <x-form.input wire:model="state.operating_hours.{{ $i }}.open_time" type="time" />
                        </x-form.field>
                        <x-form.field :label="__('editorial.close_time')" :error="$errors->first('state.operating_hours.'.$i.'.close_time')">
                            <x-form.input wire:model="state.operating_hours.{{ $i }}.close_time" type="time" />
                        </x-form.field>
                        <button type="button" wire:click="removeRow('operating_hours', {{ $i }})" class="self-end rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                    </div>
                @endforeach
                <button type="button" wire:click="addRow('operating_hours')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
            </div>

            {{-- Facilities --}}
            <div x-show="tab === 'facilities'" x-cloak class="mt-5 space-y-5">
                <div class="space-y-3">
                    <p class="text-sm font-semibold text-ink-800 dark:text-ink-200">{{ __('editorial.treatment_areas') }}</p>
                    @foreach ($state['treatment_area_list'] as $i => $row)
                        <div class="grid gap-3 rounded-xl border border-ink-100 p-3 sm:grid-cols-2 lg:grid-cols-3 dark:border-ink-800" wire:key="treatment-{{ $i }}">
                            <x-form.field :label="__('editorial.est_treatment_area_name')" :error="$errors->first('state.treatment_area_list.'.$i.'.treatment_area_name')">
                                <x-form.input wire:model="state.treatment_area_list.{{ $i }}.treatment_area_name" maxlength="255" />
                            </x-form.field>
                            <x-form.field :label="__('editorial.est_type_treatment_area')" :error="$errors->first('state.treatment_area_list.'.$i.'.type_treatment_area')">
                                <x-form.select wire:model="state.treatment_area_list.{{ $i }}.type_treatment_area" :options="$taxonomy['type_treatment_area']" :placeholder="__('editorial.select_placeholder')" />
                            </x-form.field>
                            <x-form.field :label="__('editorial.est_level_treatment_privacy')" :error="$errors->first('state.treatment_area_list.'.$i.'.level_treatment_privacy')">
                                <x-form.select wire:model="state.treatment_area_list.{{ $i }}.level_treatment_privacy" :options="$taxonomy['level_treatment_privacy']" :placeholder="__('editorial.select_placeholder')" />
                            </x-form.field>
                            <x-form.field :label="__('editorial.est_type_treatment_capacity')" :error="$errors->first('state.treatment_area_list.'.$i.'.type_treatment_capacity')">
                                <x-form.select wire:model="state.treatment_area_list.{{ $i }}.type_treatment_capacity" :options="$taxonomy['type_treatment_capacity']" :placeholder="__('editorial.select_placeholder')" />
                            </x-form.field>
                            <x-form.field :label="__('editorial.est_treatment_area_note')" :error="$errors->first('state.treatment_area_list.'.$i.'.treatment_area_note')">
                                <x-form.input wire:model="state.treatment_area_list.{{ $i }}.treatment_area_note" maxlength="255" />
                            </x-form.field>
                            <div class="flex items-end justify-end">
                                <button type="button" wire:click="removeRow('treatment_area_list', {{ $i }})" class="rounded-lg border border-ink-200 px-3 py-2 text-sm font-semibold text-ink-500 transition hover:border-ember-300 hover:text-ember-600 dark:border-ink-700 dark:text-ink-400">{{ __('editorial.remove') }}</button>
                            </div>
                        </div>
                    @endforeach
                    <button type="button" wire:click="addRow('treatment_area_list')" class="rounded-lg border border-dashed border-ink-300 px-4 py-2 text-sm font-semibold text-ink-600 transition hover:border-ember-400 hover:text-ember-600 dark:border-ink-600 dark:text-ink-300">{{ __('editorial.add_row') }}</button>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    <x-form.field :label="__('editorial.est_room_types')">
                        <x-form.toggle-group :options="$taxonomy['room_types']" model="state.room_types" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_bed_mat_chair_setup')">
                        <x-form.toggle-group :options="$taxonomy['bed_mat_chair_setup']" model="state.bed_mat_chair_setup" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_shower_availability')" :error="$errors->first('state.shower_availability')">
                        <x-form.select wire:model="state.shower_availability" :options="$taxonomy['shower_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_sauna_availability')" :error="$errors->first('state.sauna_availability')">
                        <x-form.select wire:model="state.sauna_availability" :options="$taxonomy['sauna_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_steam_room_availability')" :error="$errors->first('state.steam_room_availability')">
                        <x-form.select wire:model="state.steam_room_availability" :options="$taxonomy['steam_room_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_jacuzzi_availability')" :error="$errors->first('state.jacuzzi_availability')">
                        <x-form.select wire:model="state.jacuzzi_availability" :options="$taxonomy['jacuzzi_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_locker_availability')" :error="$errors->first('state.locker_availability')">
                        <x-form.select wire:model="state.locker_availability" :options="$taxonomy['locker_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_couple_room_availability')" :error="$errors->first('state.couple_room_availability')">
                        <x-form.select wire:model="state.couple_room_availability" :options="$taxonomy['couple_room_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_private_room_availability')" :error="$errors->first('state.private_room_availability')">
                        <x-form.select wire:model="state.private_room_availability" :options="$taxonomy['private_room_availability']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_curtain_divider_information')" :error="$errors->first('state.curtain_divider_information')">
                        <x-form.select wire:model="state.curtain_divider_information" :options="$taxonomy['curtain_divider_information']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                    <x-form.field :label="__('editorial.est_air_conditioning_information')" :error="$errors->first('state.air_conditioning_information')">
                        <x-form.select wire:model="state.air_conditioning_information" :options="$taxonomy['air_conditioning_information']" :placeholder="__('editorial.select_placeholder')" />
                    </x-form.field>
                </div>
            </div>

            {{-- Amenities & accessibility --}}
            <div x-show="tab === 'amenities'" x-cloak class="mt-5 grid gap-5 sm:grid-cols-2">
                <x-form.field :label="__('editorial.est_amenities')">
                    <x-form.toggle-group :options="$taxonomy['amenity_list']" model="state.amenity_list" />
                </x-form.field>
                <x-form.field :label="__('editorial.est_accessibility_information')">
                    <x-form.toggle-group :options="$taxonomy['accessibility_feature_list']" model="state.accessibility_feature_list" />
                </x-form.field>
            </div>

            {{-- Relationship & access (contribution flow only) --}}
            @if ($isContribution)
                <div x-show="tab === 'relationship'" x-cloak class="mt-5 space-y-5">
                    <x-form.field :label="__('workspace.contribution_relationship_label')" :error="$errors->first('type_establishment_relationship')">
                        <x-form.select wire:model="type_establishment_relationship" :options="$relationshipOptions" />
                    </x-form.field>
                    <p class="text-sm text-ink-500 dark:text-ink-400">{{ __('workspace.contribution_relationship_hint') }}</p>

                    <x-form.field :label="__('workspace.contribution_relationship_note_label')" :error="$errors->first('relationship_note')">
                        <x-form.textarea wire:model="relationship_note" rows="3" maxlength="1000" />
                    </x-form.field>

                    <div class="rounded-xl border border-ink-200 bg-ink-50 p-4 dark:border-ink-700 dark:bg-ink-800">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" wire:model="is_workspace_access_requested" class="mt-1 rounded border-ink-300 text-ember-600 focus:ring-ember-500">
                            <span>
                                <span class="block font-bold text-ink-900 dark:text-ink-100">{{ __('workspace.contribution_access_label') }}</span>
                                <span class="mt-1 block text-sm text-ink-600 dark:text-ink-300">{{ __('workspace.contribution_access_hint') }}</span>
                            </span>
                        </label>
                        @error('is_workspace_access_requested')<p class="mt-2 text-sm text-red-700 dark:text-red-300">{{ $message }}</p>@enderror
                    </div>
                </div>
            @endif
        </div>

        <div class="flex items-center justify-end gap-2.5 border-t border-ink-100 pt-5 dark:border-ink-800">
            @if ($isContribution)
                <a href="{{ route('workspace.contribution.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('workspace.contribution_cancel') }}</a>
                <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('workspace.contribution_submit') }}</button>
            @else
                <a href="{{ route('workspace.editorial.establishment.index') }}" wire:navigate class="rounded-lg border border-ink-200 px-4 py-2 text-sm font-semibold text-ink-800 transition hover:border-ink-300 hover:bg-ink-50 dark:border-ink-700 dark:text-ink-200 dark:hover:bg-ink-800">{{ __('editorial.cancel') }}</a>
                <button type="submit" class="rounded-lg bg-ember-500 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-ember-600">{{ __('editorial.save') }}</button>
            @endif
        </div>
    </form>
</div>
