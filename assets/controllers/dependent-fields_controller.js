import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["province", "city"];

    connect() {
        // console.log("Dependent fields controller connected");
    }

    fetchPoblaciones(event) {
        const province = event.target.value;
        const cityWrapper = document.getElementById('city-wrapper');
        const url = `/api/poblaciones?province=${encodeURIComponent(province)}`;

        if (!province) {
            cityWrapper.innerHTML = '<label for="volunteer_city" class="mb-1 text-sm font-medium text-gray-700 required">Población</label><input type="text" id="volunteer_city" name="volunteer[city]" required="required" class="p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">';
            return;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                let options = data.cities.map(city => `<option value="${city}">${city}</option>`).join('');
                cityWrapper.innerHTML = `
                    <label for="volunteer_city" class="mb-1 text-sm font-medium text-gray-700 required">Población</label>
                    <select id="volunteer_city" name="volunteer[city]" required="required" class="p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Selecciona una población</option>
                        ${options}
                    </select>
                `;
            })
            .catch(error => {
                console.error('Error fetching poblaciones:', error);
                cityWrapper.innerHTML = '<label for="volunteer_city" class="mb-1 text-sm font-medium text-gray-700 required">Población</a_label><input type="text" id="volunteer_city" name="volunteer[city]" required="required" class="p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Error al cargar poblaciones">';
            });
    }
}