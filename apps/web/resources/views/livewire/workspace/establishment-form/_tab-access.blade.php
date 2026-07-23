{{-- Access and delivery --}}
<div x-show="tab === 'access'" x-cloak wire:key="tab-content-access" class="mt-5 space-y-5">
    @php($deliveryIcons = [
        'OS' => 'M4 21V7l8-4 8 4v14 M9 21v-6h6v6 M4 21h16',
        'HM' => 'M4 11l8-7 8 7 M6 10v10h12V10',
        'HR' => 'M3 18v-6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v6 M3 18v2 M21 18v2 M7 10V8a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2',
        'CP' => 'M4 8h16v11H4Z M9 8V6a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2 M4 13h16',
        'EV' => 'M4 5h16v15H4Z M4 9h16 M8 3v4 M16 3v4',
        'MB' => 'M3 16V8h11v8 M14 11h4l3 3v2h-3 M6 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z M17 19a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z',
        'PA' => 'M12 21s-6-5.5-6-10a6 6 0 1 1 12 0c0 4.5-6 10-6 10Z M12 8v3l2 2',
    ])
    @php($clientFocusIcons = [
        'GP' => 'M8 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z M17 11a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z M2 20c0-3 2.5-5 6-5s6 2 6 5 M13 20c0-2.5 2-4.5 5-4.5s5 2 5 4.5',
        'MN' => 'M15 9l5-5 M16 4h4v4 M13 16a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z',
        'WM' => 'M12 14a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z M12 14v7 M9 18h6',
        'LQ' => 'M12 20s-7-4.4-7-9.5A4.5 4.5 0 0 1 12 6a4.5 4.5 0 0 1 7 4.5C19 15.6 12 20 12 20Z',
        'CP' => 'M8 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z M16 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z M3 20c0-3 2.2-5 5-5s5 2 5 5 M11 20c0-3 2.2-5 5-5s5 2 5 5',
        'FM' => 'M6.5 12a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z M5 20v-2a3 3 0 0 1 3-3h1a3 3 0 0 1 3 3v2 M17.5 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z M14 20v-1.5a2.5 2.5 0 0 1 2.5-2.5h1a2.5 2.5 0 0 1 2.5 2.5V20',
        'SR' => 'M12 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5Z M9 21l1-8 M15 21l-1-8 M10 13h4 M15 13l3 3 M18 16v5',
        'PR' => 'M12 6a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z M9 21v-5a5 5 0 0 1 4-9 5 5 0 0 1 3 9v5',
        'PN' => 'M9 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z M6 21v-6a3 3 0 0 1 3-3h0a3 3 0 0 1 3 3v6 M17 20a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z M17 17v-2a2 2 0 0 0-2-2',
        'AT' => 'M4 12h2 M18 12h2 M7 9v6 M17 9v6 M9 12h6',
        'TR' => 'M12 21a9 9 0 1 0 0-18 9 9 0 0 0 0 18Z M3 12h18 M12 3a13 13 0 0 1 0 18 M12 3a13 13 0 0 0 0 18',
        'OW' => 'M4 16h16 M6 16V6h12v10 M2 20h20',
    ])
    <x-form.field :label="__('editorial.est_mode_service_delivery')">
        <x-form.toggle-group :options="$taxonomy['mode_service_delivery']" model="state.mode_service_delivery" :live="true" :icons="$deliveryIcons" />
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
        <x-form.toggle-group :options="$taxonomy['target_client_focus']" model="state.target_client_focus" :icons="$clientFocusIcons" />
    </x-form.field>
</div>
