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

        // Initialize Modal
        this.confirmModal = null;
        if (typeof bootstrap !== 'undefined') {
            const modalEl = document.getElementById('transferConfirmModal');
            if (modalEl) {
                this.confirmModal = new bootstrap.Modal(modalEl);
            }
        }
    }

    addRow() {
        const template = this.prototypeTarget.innerHTML;
        this.containerTarget.insertAdjacentHTML('beforeend', template);

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
            html += `<option value="">No hay stock disponible</option>`;
        } else {
            options.forEach(opt => {
                const style = opt.busy ? 'style="color: #f87171 !important; font-weight: bold;"' : '';
                const busyAttr = opt.busy ? 'data-busy="true"' : 'data-busy="false"';
                const locAttr = opt.locationName ? `data-location-name="${opt.locationName}"` : '';
                const labelSuffix = nature === 'CONSUMIBLE' ? `(Disp: ${opt.available})` : (opt.busy ? ` (OCUPADO: ${opt.locationName})` : '');

                html += `<option value="${opt.id}" data-available="${opt.available}" ${busyAttr} ${locAttr} ${style}>${opt.label} ${labelSuffix}</option>`;
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
        const selectedOption = select.options[select.selectedIndex];
        const row = select.closest('.refill-row');

        // Store original value to revert if needed
        if (!select.dataset.lastValid) {
            select.dataset.lastValid = select.value;
        }

        // Requirement: Warning if an occupied unit is selected
        if (row.dataset.nature !== 'CONSUMIBLE' && row.dataset.nature !== 'OTROS' && selectedOption.dataset.busy === 'true') {
            const kitName = selectedOption.dataset.locationName || 'otro botiquín';
            const message = `Este material ya está asignado al botiquín [${kitName}]. ¿Deseas transferirlo a este nuevo dispositivo?`;

            if (this.confirmModal) {
                document.getElementById('transferModalMessage').textContent = message;
                this.confirmModal.show();

                const confirmBtn = document.getElementById('confirmTransferBtn');
                const onConfirm = () => {
                    select.dataset.lastValid = select.value;
                    this.confirmModal.hide();
                    confirmBtn.removeEventListener('click', onConfirm);
                };

                confirmBtn.addEventListener('click', onConfirm);

                const modalEl = document.getElementById('transferConfirmModal');
                const onHide = () => {
                    if (select.value !== select.dataset.lastValid) {
                        select.value = select.dataset.lastValid;
                    }
                    modalEl.removeEventListener('hidden.bs.modal', onHide);
                    confirmBtn.removeEventListener('click', onConfirm);
                };
                modalEl.addEventListener('hidden.bs.modal', onHide);

            } else {
                // Fallback to confirm if bootstrap modal is not available
                if (!confirm(message)) {
                    select.value = select.dataset.lastValid;
                } else {
                    select.dataset.lastValid = select.value;
                }
            }
        } else {
            select.dataset.lastValid = select.value;
        }

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
