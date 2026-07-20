<div class="space-y-6">
    @foreach ($widgets as $widget)
        <x-dynamic-component :component="'widgets.' . $widget" />
    @endforeach
</div>