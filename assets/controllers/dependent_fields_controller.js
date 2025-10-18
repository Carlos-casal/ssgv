import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["province", "city"];
    static values = {
        url: String
    };

    async loadCities() {
        // The event is triggered on the wrapper div, so we find the select inside.
        const provinceSelect = this.provinceTarget.querySelector('select');
        const citySelect = this.cityTarget.querySelector('select');
        const province = provinceSelect.value;

        citySelect.innerHTML = '<option value="">Cargando...</option>';
        citySelect.disabled = true;

        if (!province) {
            citySelect.innerHTML = '<option value="">Selecciona una provincia primero</option>';
            return;
        }

        const url = `${this.urlValue}?province=${encodeURIComponent(province)}`;

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const cities = await response.json();

            citySelect.innerHTML = ''; // Clear "Cargando..."

            // The placeholder is already set in VolunteerType.php,
            // but we add a default option if needed.
            const placeholderText = citySelect.getAttribute('placeholder') || 'Selecciona una población';
            citySelect.add(new Option(placeholderText, ''));

            if (cities.length > 0) {
                cities.forEach(city => {
                    citySelect.add(new Option(city.name, city.name));
                });
                citySelect.disabled = false;
            } else {
                citySelect.innerHTML = '<option value="">No hay poblaciones disponibles</option>';
            }

        } catch (error) {
            console.error("Could not fetch cities:", error);
            citySelect.innerHTML = '<option value="">Error al cargar poblaciones</option>';
        }
    }
}