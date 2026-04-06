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
        if (nature === 'EQUIPO_TECNICO') {
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
            select.dataset.lastValid = select.value === selectedOption.value ? select.dataset.initialValue || select.value : select.value;
        }

        // Requirement: Warning if an occupied unit is selected
        if (row.dataset.nature !== 'CONSUMIBLE' && row.dataset.nature !== 'OTROS' && selectedOption.dataset.busy === 'true') {
            const kitName = selectedOption.dataset.locationName || 'otro botiquín';
            
            Swal.fire({
                title: '¿Confirmar Traslado?',
                text: `Este material ya está asignado al botiquín [${kitName}]. ¿Deseas transferirlo a este nuevo dispositivo?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f87171',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'SÍ, TRASLADAR',
                cancelButtonText: 'CANCELAR',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    confirmButton: 'rounded-3 font-bold px-4 py-2',
                    cancelButton: 'rounded-3 font-bold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    select.dataset.lastValid = select.value;
                } else {
                    select.value = select.dataset.lastValid;
                }
            });
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

        if (nature === 'EQUIPO_TECNICO') {
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
                unit_id: nature === 'EQUIPO_TECNICO' ? identifierSelect.value : null
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
        const colors = {
            info: '#3b82f6',
            warning: '#f59e0b',
            danger: '#ef4444',
            success: '#10b981'
        };

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                icon: type === 'danger' ? 'error' : type,
                title: message,
                background: '#fff',
                color: '#1e293b'
            });
        } else {
            console.log(`[Refill] ${type}: ${message}`);
            if (type === 'warning' || type === 'danger') {
                alert(message);
            }
        }
    }

    forceSelect(event) {
        const { materialId, unitId } = event.currentTarget.dataset;
        
        // 1. Find a row for this material that doesn't have a unit selected yet (placeholder)
        let targetRow = Array.from(this.containerTarget.querySelectorAll('.refill-row')).find(row => {
            const rowMatId = row.dataset.materialId || row.querySelector('.material-id')?.value;
            const select = row.querySelector('.identifier-select');
            return rowMatId === materialId && (!select || !select.value || select.options[select.selectedIndex]?.value === '');
        });

        if (!targetRow) {
            // 2. If no semi-empty row found, add a new one
            this.addRow();
            targetRow = this.containerTarget.lastElementChild;
            const matSelect = targetRow.querySelector('.material-selector');
            if (matSelect) {
                matSelect.value = materialId;
                this.onMaterialChange({ currentTarget: matSelect });
            }
        }

        // 3. Select the specific unit
        const select = targetRow.querySelector('.identifier-select');
        if (select) {
            select.value = unitId;
            this.updateAvailable({ currentTarget: select });
        }
        
        // 4. Visual feedback
        targetRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
        targetRow.classList.add('bg-yellow-50', 'dark:bg-yellow-900/20', 'transition-colors');
        setTimeout(() => targetRow.classList.remove('bg-yellow-50', 'dark:bg-yellow-900/20'), 2000);
    }
}
