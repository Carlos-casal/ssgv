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

        if (!province) {
            this._clearAndDisable(citySelect, 'Selecciona una provincia primero');
            return;
        }

        const url = this.urlValue + '?province=' + encodeURIComponent(province);

        try {
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const cities = await response.json();

            this._clearAndEnable(citySelect, 'Selecciona una población');
            cities.forEach(city => {
                const option = new Option(city.name, city.name);
                citySelect.add(option);
            });

        } catch (error) {
            console.error("Could not fetch cities:", error);
            this._clearAndDisable(citySelect, 'Error al cargar poblaciones');
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