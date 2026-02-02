import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container", "quantity"];

    generateRows() {
        const quantity = parseInt(this.quantityTarget.value);
        if (isNaN(quantity) || quantity <= 0) {
            this.containerTarget.innerHTML = '';
            return;
        }

        let html = '';
        for (let i = 0; i < quantity; i++) {
            html += `
                <div class="row mb-3 p-3 border rounded bg-light mx-0">
                    <div class="col-md-3">
                        <label class="form-label">Nº Colectiva</label>
                        <input type="text" name="units[${i}][collectiveNumber]" class="form-control" placeholder="Ej: C-01">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Nº de Serie (M/S)</label>
                        <input type="text" name="units[${i}][serialNumber]" class="form-control" placeholder="S/N">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">PTT</label>
                        <select name="units[${i}][pttStatus]" class="form-select">
                            <option value="OK">OK</option>
                            <option value="Falla">Falla</option>
                            <option value="No tiene">No tiene</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Funda</label>
                        <select name="units[${i}][coverStatus]" class="form-select">
                            <option value="OK">OK</option>
                            <option value="Dañada">Dañada</option>
                            <option value="No tiene">No tiene</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Batería</label>
                        <select name="units[${i}][batteryStatus]" class="form-select">
                            <option value="100%">100%</option>
                            <option value="75%">75%</option>
                            <option value="50%">50%</option>
                            <option value="25%">25%</option>
                            <option value="Cambio necesario">Cambio necesario</option>
                        </select>
                    </div>
                </div>
            `;
        }
        this.containerTarget.innerHTML = html;
    }
}
