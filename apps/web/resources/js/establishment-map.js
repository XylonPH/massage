import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

function initMapPicker(container) {
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

document.addEventListener('livewire:navigated', () => {
    document.querySelectorAll('[data-map-picker]').forEach(initMapPicker);
});
document.querySelectorAll('[data-map-picker]').forEach(initMapPicker);
