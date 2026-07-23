{{-- Accessibility --}}
<div x-show="tab === 'accessibility'" x-cloak wire:key="tab-content-accessibility" class="mt-5 space-y-5">
    @php($accessibilityIcons = [
        'SFE' => 'M3 20h18 M3 20 15 8h4v12',
        'ELV' => 'M5 4h14v16H5Z M9 10l3-3 3 3 M9 14l3 3 3-3',
        'AWA' => 'M13 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z M11 8h4l3 6 M9 21a5 5 0 1 1 5-5',
        'ATR' => 'M6 3h9v18H6Z M9 21a4 4 0 1 0 0-8',
        'AP' => 'M6 4h5a3 3 0 0 1 0 6H9v8H6Z M17 15a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z',
        'ADO' => 'M3 13l2-5h10l2 5 M4 13h14v4H4Z M7 17v2 M15 17v2 M10 6l2 2 2-2',
        'MOB' => 'M12 4a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z M9 21l2-9 M15 21l-3-9-4 2 M9 12l6-1',
        'VCA' => 'M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7Z M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z',
        'HCA' => 'M8 6a5 5 0 0 1 8 4c0 3-2 3-2 6a2 2 0 0 1-4 0 M6 12a4 4 0 0 0 4 4',
        'SA' => 'M8 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z M12 8a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z M16 9a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z M12 17c-2.5 0-4-1.5-4-3.5S9.5 11 12 11s4 1 4 2.5S14.5 17 12 17Z',
    ])
    <x-form.field :label="__('editorial.est_accessibility_information')">
        <x-form.toggle-group :options="$taxonomy['accessibility_feature_list']" model="state.accessibility_feature_list" :icons="$accessibilityIcons" />
    </x-form.field>
</div>
