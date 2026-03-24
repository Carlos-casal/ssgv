import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'prototype'];
    static values = {
        originId: String
    };

    connect() {
        this.warehouseOptions = JSON.parse(document.getElementById('warehouse-options-data').textContent);
        this.updateAllAvailable();
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    addRow() {
        const template = this.prototypeTarget.innerHTML;
        const newRow = document.createElement('tr');
        newRow.className = 'refill-row';
        newRow.innerHTML = template;
        this.containerTarget.appendChild(newRow);

        const emptyRow = this.containerTarget.querySelector('.empty-row');
        if (emptyRow) emptyRow.remove();

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    removeRow(event) {
        event.preventDefault();
        event.currentTarget.closest('.refill-row').remove();
    }

    onMaterialChange(event) {
        const select = event.currentTarget;
        const row = select.closest('.refill-row');
        const materialId = select.value;
        const nature = select.options[select.selectedIndex].dataset.nature;

        row.dataset.materialId = materialId;
        row.dataset.nature = nature;

        const identifierContainer = row.querySelector('.identifier-container');
        const options = this.warehouseOptions[materialId] || [];

        let html = `<select class="form-select form-select-sm identifier-select" data-action="change->kit-refill#updateAvailable">`;
        if (options.length === 0) {
            html += `<option value="">No hay stock en almacén</option>`;
        } else {
            options.forEach(opt => {
                html += `<option value="${opt.id}" data-available="${opt.available}">${opt.label} (Disp: ${opt.available})</option>`;
            });
        }
        html += `</select>`;
        identifierContainer.innerHTML = html;

        const quantityInput = row.querySelector('.quantity-input');
        if (nature === 'EQUIPO') {
            quantityInput.value = 1;
            quantityInput.readOnly = true;
        } else {
            quantityInput.readOnly = false;
        }
    }

    updateAvailable(event) {
        const select = event.currentTarget;
        const row = select.closest('.refill-row');
        this.validateQuantity({ target: row.querySelector('.quantity-input') });
    }

    validateQuantity(event) {
        const input = event.target;
        const row = input.closest('.refill-row');
        const select = row.querySelector('.identifier-select');
        const nature = row.dataset.nature;

        if (!select || !select.options[select.selectedIndex]) return;

        const available = parseInt(select.options[select.selectedIndex].dataset.available || 0);
        let value = parseInt(input.value);

        if (nature === 'EQUIPO') {
            input.value = 1;
        } else if (value > available) {
            input.value = available;
            this.showToast(`Stock insuficiente. Máximo disponible: ${available}`, 'warning');
        }
    }

    updateAllAvailable() {
        this.containerTarget.querySelectorAll('.refill-row').forEach(row => {
            const input = row.querySelector('.quantity-input');
            if (input) this.validateQuantity({ target: input });
        });
    }

    submit(event) {
        const proposals = [];
        this.containerTarget.querySelectorAll('.refill-row').forEach(row => {
            const materialId = row.dataset.materialId || row.querySelector('.material-id')?.value;
            const nature = row.dataset.nature;
            const identifierSelect = row.querySelector('.identifier-select');
            const quantity = parseInt(row.querySelector('.quantity-input').value);

            if (!materialId || !identifierSelect || !identifierSelect.value) return;

            const proposal = {
                material_id: materialId,
                origin_id: this.originIdValue,
                quantity: quantity,
                batch_id: nature === 'CONSUMIBLE' ? (identifierSelect.value === 'NO_BATCH' ? null : identifierSelect.value) : null,
                unit_id: nature === 'EQUIPO' ? identifierSelect.value : null
            };
            proposals.push(proposal);
        });

        if (proposals.length === 0) {
            event.preventDefault();
            this.showToast('No hay transferencias válidas para confirmar.', 'danger');
            return;
        }

        document.getElementById('proposals_data').value = JSON.stringify(proposals);
    }

    showToast(message, type = 'info') {
        // Simple alert as fallback, assuming the project has a toast system but not specified
        console.log(`[Refill] ${type}: ${message}`);
        if (type === 'warning' || type === 'danger') {
            alert(message);
        }
    }
}
