{{-- Amenities and accessibility --}}
<div x-show="tab === 'amenities'" x-cloak class="mt-5 grid gap-5 sm:grid-cols-2">
    <x-form.field :label="__('editorial.est_amenities')">
        <x-form.toggle-group :options="$taxonomy['amenity_list']" model="state.amenity_list" />
    </x-form.field>
    <x-form.field :label="__('editorial.est_accessibility_information')">
        <x-form.toggle-group :options="$taxonomy['accessibility_feature_list']" model="state.accessibility_feature_list" />
    </x-form.field>
</div>
