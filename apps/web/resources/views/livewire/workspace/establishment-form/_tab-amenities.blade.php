{{-- Amenities --}}
@if ($this->hasPhysicalPremises())
<div x-show="tab === 'amenities'" x-cloak wire:key="tab-content-amenities" class="mt-5 space-y-5">
    @php($amenityIcons = [
        'WIFI' => 'M2 8.5a15 15 0 0 1 20 0 M5.5 12a10 10 0 0 1 13 0 M12 16.5h.01',
        'PRK' => 'M6 4h5a3 3 0 0 1 0 6H9v8H6Z',
        'WTEA' => 'M5 8h10v6a4 4 0 0 1-4 4H9a4 4 0 0 1-4-4Z M15 9h2a2 2 0 0 1 0 4h-2',
        'SLIP' => 'M4 15c0-4 3-8 8-8s8 4 8 8-3 5-8 5-8-1-8-5Z M12 9v3 M10 13l2 2 2-2',
        'TOWL' => 'M4 7h16 M4 12h16 M4 17h10',
        'SROB' => 'M12 4a2 2 0 1 1-2 2 M12 6l8 6H4l8-6Z M6 20h12l-2-6H8Z',
        'WSHR' => 'M7 3h10v18H7Z M15 12h.01',
        'LOUNGE' => 'M4 17v-4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4 M3 17h18 M6 11V8a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v3',
    ])
    <x-form.field :label="__('editorial.est_amenities')">
        <x-form.toggle-group :options="$taxonomy['amenity_list']" model="state.amenity_list" :icons="$amenityIcons" />
    </x-form.field>
</div>
@endif
