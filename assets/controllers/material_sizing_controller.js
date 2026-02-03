import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["sizingType", "gridContainer", "stockInput"];

    connect() {
        this.updateGrid();
    }

    updateGrid() {
        const type = this.sizingTypeTarget.value;
        if (!type) {
            this.gridContainerTarget.innerHTML = '';
            return;
        }

        let sizes = [];
        if (type === 'LETTER') {
            sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL'];
        } else if (type === 'NUMBER_CLOTHING') {
            for (let i = 32; i <= 60; i += 2) sizes.push(i);
        } else if (type === 'NUMBER_SHOES') {
            for (let i = 35; i <= 48; i++) sizes.push(i);
        }

        let html = '<div class="card bg-light border-0 shadow-sm mt-3"><div class="card-body">';
        html += '<h6 class="font-bold mb-3 text-primary">Inicializar Stock por Tallas (Opcional)</h6>';
        html += '<div class="row g-2">';

        sizes.forEach(size => {
            html += `
                <div class="col-md-2 col-4">
                    <label class="form-label small font-bold mb-0">Talla ${size}</label>
                    <input type="number" name="initial_stock[${size}]" class="form-control form-control-sm" value="0" min="0" data-action="input->material-sizing#calculateTotal">
                </div>
            `;
        });

        html += `
                <div class="col-md-4 col-12 mt-2">
                    <label class="form-label small font-bold mb-0">Otra / Manual</label>
                    <div class="input-group input-group-sm">
                        <input type="text" name="custom_size" class="form-control" placeholder="Talla">
                        <input type="number" name="custom_qty" class="form-control w-25" value="0" min="0" data-action="input->material-sizing#calculateTotal">
                    </div>
                </div>
        `;

        html += '</div></div></div>';
        this.gridContainerTarget.innerHTML = html;
    }

    calculateTotal() {
        let total = 0;
        const inputs = this.gridContainerTarget.querySelectorAll('input[type="number"]');
        inputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        if (this.hasStockInputTarget) {
            this.stockInputTarget.value = total;
        }
    }
}
