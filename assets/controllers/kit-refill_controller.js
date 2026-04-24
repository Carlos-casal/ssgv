import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['container', 'prototype'];
    static values = {
        originId: String
    };

    connect() {
        this.warehouseOptions = JSON.parse(document.getElementById('warehouse-options-data').textContent);
        this.updateAllAvailable();
        this.validateAllRows();

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
        const row = event.currentTarget.closest('.refill-row');
        row.remove();
        this.validateAllRows();
    }

    onMaterialChange(event) {
        const select = event.currentTarget;
        const row = select.closest('.refill-row');
        const materialId = select.value;
        const nature = select.options[select.selectedIndex].dataset.nature;

        row.dataset.materialId = materialId;
        row.dataset.nature = nature;

        const manualTitle = row.querySelector('.manual-title');
        if (manualTitle) {
            manualTitle.textContent = select.options[select.selectedIndex].text;
            manualTitle.style.display = 'block';
            select.style.display = 'none';
        }

        const identifierContainer = row.querySelector('.identifier-container');
        const options = this.warehouseOptions[materialId] || [];

        let html = `<select class="form-select form-select-sm identifier-select" data-action="change->kit-refill#updateAvailable">`;
        if (options.length === 0) {
            html += `<option value="">No hay stock disponible</option>`;
        } else {
            options.forEach(opt => {
                const style = opt.busy ? 'style="color: #dc2626 !important; font-weight: bold;"' : '';
                const busyAttr = opt.busy ? 'data-busy="true"' : 'data-busy="false"';
                const locAttr = opt.locationName ? `data-location-name="${opt.locationName}"` : '';
                const locIdAttr = opt.locationId ? `data-location-id="${opt.locationId}"` : '';
                const batchIdAttr = opt.batch_id ? `data-batch-id="${opt.batch_id}"` : '';
                const labelSuffix = nature === 'CONSUMIBLE' ? `(Disp: ${opt.available})` : (opt.busy ? ` (OCUPADO: ${opt.locationName})` : '');

                html += `<option value="${opt.id}" data-available="${opt.available}" ${busyAttr} ${locAttr} ${locIdAttr} ${batchIdAttr} ${style}>${opt.label} ${labelSuffix}</option>`;
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

        select.dataset.lastValid = select.value;

        this.validateQuantity({ target: row.querySelector('.quantity-input') });
        this.validateAllRows();
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

    validateAllRows() {
        let hasBusySelection = false;
        const rows = this.containerTarget.querySelectorAll('.refill-row');

        rows.forEach(row => {
            const select = row.querySelector('.identifier-select');
            const locationLabel = row.querySelector('.location-label');

            if (select && select.options[select.selectedIndex]) {
                const opt = select.options[select.selectedIndex];
                const isBusy = opt.dataset.busy === 'true';
                const locationName = opt.dataset.locationName || '';

                if (isBusy) {
                    hasBusySelection = true;
                    row.classList.add('bg-red-50', 'dark:bg-red-900/10');
                } else {
                    row.classList.remove('bg-red-50', 'dark:bg-red-900/10');
                }

                if (locationLabel) {
                    if (locationName) {
                        locationLabel.innerHTML = `<i data-lucide="map-pin" class="w-2 h-2 inline"></i> Ubicación: ${locationName}`;
                        locationLabel.classList.toggle('text-red-500', isBusy);
                        locationLabel.classList.toggle('text-slate-400', !isBusy);
                    } else {
                        locationLabel.innerHTML = '';
                    }
                }
            }
        });

        // Update UI Icons for dynamic labels
        if (typeof lucide !== 'undefined') lucide.createIcons();

        // Update UI state based on selection
        const submitBtn = document.querySelector('form[data-action="submit->kit-refill#submit"] button[type="submit"]');
        if (submitBtn) {
            this.showBusyWarning(hasBusySelection);
        }
    }

    showBusyWarning(show) {
        let alert = document.getElementById('busy-warning-alert');
        if (show) {
            if (!alert) {
                alert = document.createElement('div');
                alert.id = 'busy-warning-alert';
                alert.className = 'alert alert-warning mb-4 shadow-sm animate__animated animate__fadeIn';
                alert.innerHTML = `
                    <div class="d-flex align-items-center">
                        <i data-lucide="info" class="w-5 h-5 mr-3"></i>
                        <div>
                            <div class="font-black text-xs uppercase tracking-wider">Aviso de Traslado</div>
                            <div class="small">Has seleccionado unidades que están en otros botiquines o vehículos. Al guardar, se realizará un traspaso automático.</div>
                        </div>
                    </div>
                `;
                this.containerTarget.closest('.card').insertAdjacentElement('beforebegin', alert);
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }
        } else if (alert) {
            alert.remove();
        }
    }

    submit(event) {
        const form = event.currentTarget;
        if (form.dataset.confirmed === 'true') {
            return;
        }

        event.preventDefault();
        const proposals = [];
        let hasBusy = false;
        const busyDetails = [];

        this.containerTarget.querySelectorAll('.refill-row').forEach(row => {
            const materialId = row.dataset.materialId || row.querySelector('.material-id')?.value;
            const rawNature = row.dataset.nature || '';
            const nature = rawNature.trim().toUpperCase();
            const identifierSelect = row.querySelector('.identifier-select');
            
            const quantityInput = row.querySelector('.quantity-input');
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            if (!materialId || !identifierSelect || !identifierSelect.value || identifierSelect.value === 'NONE') return;

            const selectedOption = identifierSelect.options[identifierSelect.selectedIndex];
            if (!selectedOption || !selectedOption.value) return;

            const isBusy = selectedOption.dataset.busy === 'true';
            if (isBusy) {
                hasBusy = true;
                busyDetails.push({
                    label: selectedOption.text,
                    location: selectedOption.dataset.locationName
                });
            }

            const isConsumable = (nature === 'CONSUMIBLE');
            const proposal = {
                material_id: materialId,
                origin_id: selectedOption.dataset.locationId || this.originIdValue,
                stock_id: isConsumable ? selectedOption.value : null,
                quantity: quantity,
                batch_id: selectedOption.dataset.batchId && selectedOption.dataset.batchId !== 'NO_BATCH' ? selectedOption.dataset.batchId : null,
                unit_id: !isConsumable ? selectedOption.value : null
            };
            proposals.push(proposal);
        });

        if (proposals.length === 0) {
            this.showToast('No hay transferencias válidas para confirmar.', 'danger');
            return;
        }

        document.getElementById('proposals_data').value = JSON.stringify(proposals);

        if (hasBusy) {
            const listHtml = busyDetails.map(d => `<li><b>${d.label}</b> (en ${d.location})</li>`).join('');

            Swal.fire({
                title: 'Confirmar Traspaso Exclusivo',
                html: `
                    <div class="text-start">
                        <p>Las siguientes unidades serán retiradas de su ubicación actual y <b>dejarán de estar disponibles en su origen</b> inmediatamente:</p>
                        <ul class="small mb-3">${listHtml}</ul>
                        <p class="mb-0">¿Deseas confirmar el traslado a este nuevo kit?</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'SÍ, TRASLADAR Y GUARDAR',
                cancelButtonText: 'CANCELAR',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    confirmButton: 'rounded-3 font-bold px-4 py-2',
                    cancelButton: 'rounded-3 font-bold px-4 py-2'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.dataset.confirmed = 'true';
                    form.submit();
                }
            });
        } else {
            form.dataset.confirmed = 'true';
            form.submit();
        }
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