import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

function initMapPicker(container) {
    // Livewire AJAX re-renders (e.g. the contribution wizard's nextStep()) and full
    // SPA navigations can both surface the same already-initialized container more
    // than once; Leaflet throws if L.map() runs twice on the same element.
    if (container.dataset.mapInitialized === 'true') {
        return;
    }
    container.dataset.mapInitialized = 'true';

    const canvas = container.querySelector('[data-map-picker-canvas]');
    const latInputName = container.dataset.latInput;
    const lngInputName = container.dataset.lngInput;
    const lat = parseFloat(container.dataset.lat);
    const lng = parseFloat(container.dataset.lng);

    const map = L.map(canvas).setView([lat, lng], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
    }).addTo(map);

    const marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    const component = window.Livewire.find(container.closest('[wire\\:id]').getAttribute('wire:id'));

    marker.on('dragend', () => {
        const { lat, lng } = marker.getLatLng();
        component.set(latInputName, Number(lat.toFixed(6)));
        component.set(lngInputName, Number(lng.toFixed(6)));
    });

    map.on('click', (event) => {
        marker.setLatLng(event.latlng);
        component.set(latInputName, Number(event.latlng.lat.toFixed(6)));
        component.set(lngInputName, Number(event.latlng.lng.toFixed(6)));
    });
}

function scanAndInitMapPickers(root) {
    if (!root || typeof root.querySelectorAll !== 'function') {
        return;
    }

    if (typeof root.matches === 'function' && root.matches('[data-map-picker]')) {
        initMapPicker(root);
    }

    root.querySelectorAll('[data-map-picker]').forEach(initMapPicker);
}

// Full-page (SPA) navigations replace the DOM wholesale; re-scan the whole document.
document.addEventListener('livewire:navigated', () => {
    scanAndInitMapPickers(document);
});

// The contribution wizard's Location tab only enters the DOM once currentStep
// reaches 2, and that transition happens via nextStep(), an AJAX-driven Livewire
// commit — not a page navigation, so `livewire:navigated` never fires for it.
// `morphed` fires once per component after each commit finishes morphing the DOM,
// with that component's own root element, so re-scanning it here catches map
// pickers that just appeared without a full-page load.
document.addEventListener('livewire:init', () => {
    window.Livewire.hook('morphed', ({ el }) => {
        scanAndInitMapPickers(el);
    });
});

// Editorial direct-edit mode has no step concept, so the Location tab (and its map
// picker) is already present in the initial server-rendered HTML — cover that case
// with an immediate scan on script load, same as before.
scanAndInitMapPickers(document);
