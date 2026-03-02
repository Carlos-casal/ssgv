import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['unitsContainer', 'stockInput', 'totalPrice', 'unitPrice', 'sizingType', 'sizingGrid'];

    connect() {
        console.log("Material Dynamic Form Controller Connected");
        this.handleStockChange();
        this.calculateUnitPrice();
    }

    handleStockChange() {
        const stockInput = this.hasStockInputTarget ? this.stockInputTarget : null;
        if (!stockInput) return;

        const stock = parseInt(stockInput.value) || 0;
        const category = this.element.dataset.materialCategory;

        console.log("Stock change detected:", stock, "Category:", category);

        if (category === 'Comunicaciones') {
            this.generateUnitFields(stock);
        }

        this.calculateUnitPrice();
    }

    handleSizingChange() {
        this.updateSizingGrid();
    }

    generateUnitFields(count) {
        if (!this.hasUnitsContainerTarget) return;

        // Limit count to avoid browser crash/abuse
        if (count > 100) count = 100;

        // In edit mode, we might already have units.
        // We only want to generate fields for NEW units if stock increases.
        // However, the current backend logic expects units_data to be the FULL set if we want to sync,
        // but my latest controller fix only creates if count > existing.
        // So let's only generate fields for the DELTA if in edit mode,
        // OR better, generate for all but mark existing ones?
        // Simpler: the user only adds NEW units via this dynamic form in New/Edit.
        const currentData = {};
        this.unitsContainerTarget.querySelectorAll('input').forEach(input => {
            currentData[input.name] = input.value;
        });

        let html = '';
        if (count > 0) {
            html += `<div class="card shadow-sm mb-4 border-0 border-left-warning">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-list-ol mr-2"></i> IDENTIFICACIÓN DE UNIDADES (${count})
                    </h6>
                </div>
                <div class="card-body">`;

            for (let i = 0; i < count; i++) {
                const aliasName = `units_data[${i}][alias]`;
                const snName = `units_data[${i}][serialNumber]`;
                const netName = `units_data[${i}][networkId]`;
                const phoneName = `units_data[${i}][phoneNumber]`;

                html += `
                    <div class="unit-row p-3 mb-3 border rounded bg-light">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="small font-weight-bold">NOMBRE / ALIAS EN RED</label>
                                <input type="text" name="${aliasName}" value="${currentData[aliasName] || ''}" class="form-control form-control-sm" placeholder="Ej: ALFA 1">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Nº SERIE (S/N)</label>
                                <input type="text" name="${snName}" value="${currentData[snName] || ''}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">ID RED (ISSI/IMEI)</label>
                                <input type="text" name="${netName}" value="${currentData[netName] || ''}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3">
                                <label class="small font-weight-bold">Nº TELÉFONO</label>
                                <input type="text" name="${phoneName}" value="${currentData[phoneName] || ''}" class="form-control form-control-sm" placeholder="+34...">
                            </div>
                        </div>
                    </div>`;
            }
            html += `</div></div>`;
        }
        this.unitsContainerTarget.innerHTML = html;
    }

    updateSizingGrid() {
        if (!this.hasSizingTypeTarget || !this.hasSizingGridTarget) return;

        const type = this.sizingTypeTarget.value;
        if (!type) {
            this.sizingGridTarget.innerHTML = '';
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

        let html = `
            <div class="card shadow-sm mb-4 border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary">REPARTO POR TALLAS (TOTAL: <span id="sizing-total-display">0</span>)</h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">`;

        sizes.forEach(size => {
            html += `
                <div class="col-md-2 col-4 mb-2">
                    <label class="form-label small font-bold mb-0">Talla ${size}</label>
                    <input type="number" name="initial_stock[${size}]" class="form-control form-control-sm sizing-input" value="0" min="0" data-action="input->material-dynamic-form#syncStockFromGrid">
                </div>`;
        });

        html += `
                    </div>
                </div>
            </div>`;

        this.sizingGridTarget.innerHTML = html;
        this.syncStockFromGrid();
    }

    syncStockFromGrid() {
        let total = 0;
        const inputs = this.sizingGridTarget.querySelectorAll('.sizing-input');
        inputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        if (this.hasStockInputTarget) {
            this.stockInputTarget.value = total;
        }
        const display = document.getElementById('sizing-total-display');
        if (display) display.textContent = total;
        this.calculateUnitPrice();
    }

    calculateUnitPrice() {
        if (!this.hasTotalPriceTarget || !this.hasUnitPriceTarget || !this.hasStockInputTarget) return;

        const total = parseFloat(this.totalPriceTarget.value.replace(',', '.')) || 0;
        const stock = parseInt(this.stockInputTarget.value) || 0;

        if (stock > 0) {
            this.unitPriceTarget.value = (total / stock).toFixed(2);
        } else {
            this.unitPriceTarget.value = '0.00';
        }
    }

    handleIvaChange() {
        // Future polish: could auto-calculate Total from Price+IVA or vice versa
        this.calculateUnitPrice();
    }

    handleMaintenanceSync(event) {
        // Apply "all or none" logic if applicable, but per requirements:
        // "Al marcar uno, se aplica a todos los del lote."
        // For simplicity in creation, these are global fields in Panel C that apply to the whole Material.
        // If we want to sync them to individual units later, that's fine.
    }
}
