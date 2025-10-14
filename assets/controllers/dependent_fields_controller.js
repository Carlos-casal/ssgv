import { Controller } from '@hotwired/stimulus';

/**
 * Controller to handle dependent form fields, where the options of one field
 * (e.g., city) depend on the selected value of another (e.g., province).
 */
export default class extends Controller {
    static targets = ["province", "city"];
    static values = {
        url: String
    };

    connect() {
        if (!this.hasUrlValue) {
            console.error('The URL value is missing for the dependent fields controller.');
            return;
        }
    }

    /**
     * Fetches and loads the cities for the selected province.
     */
    async loadCities() {
        const province = this.provinceTarget.value;
        const citySelect = this.cityTarget;

        // Always clear previous options and set a default state
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

            citySelect.innerHTML = ''; // Clear the "Cargando..." message
            citySelect.add(new Option('Población', ''));

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

    _clearAndDisable(selectElement, placeholder) {
        selectElement.innerHTML = '';
        const defaultOption = new Option(placeholder, '');
        selectElement.add(defaultOption);
        selectElement.disabled = true;
    }

    _clearAndEnable(selectElement, placeholder) {
        selectElement.innerHTML = '';
        const defaultOption = new Option(placeholder, '');
        selectElement.add(defaultOption);
        selectElement.disabled = false;
    }
}