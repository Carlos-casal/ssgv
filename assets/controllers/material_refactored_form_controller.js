import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        'dynamicBlocksContainer', 'form'
    ];

    connect() {
        // ULTIMATE DEBUG
        window.alert('Stimulus: material-refactored-form CONNECTED');
        console.log("Material Refactored Form Connected");
        
        this.renderDynamicBlock();
        this.recalculate();
    }

    handleNatureChange() {
        window.alert('Stimulus: Nature Changed');
        this.renderDynamicBlock();
        this.recalculate();
    }

    renderDynamicBlock() {
        const natureSelect = this.element.querySelector('select[name*="[nature]"]') || 
                           this.element.querySelector('[data-action*="handleNatureChange"]');
        
        if (!natureSelect) {
            console.error("Nature select not found");
            return;
        }
        
        const nature = natureSelect.value;
        const container = this.dynamicBlocksContainerTarget;
        if (!container) {
            console.error("Container not found");
            return;
        }

        const initialUnits = this.element.dataset.initialUnits;
        const initialBatches = this.element.dataset.initialBatches;

        container.innerHTML = '';
        
        if (nature === 'CONSUMIBLE') {
            const units = initialBatches ? JSON.parse(initialBatches) : [];
            if (units.length > 0) {
                units.forEach(batch => this.addBatchRow(batch));
            } else {
                this.addBatchRow();
            }
        } else if (nature === 'EQUIPO_TECNICO') {
            const units = initialUnits ? JSON.parse(initialUnits) : [];
            if (units.length > 0) {
                units.forEach(unit => this.addUnitRow(unit));
            } else {
                this.addUnitRow();
            }
        } else if (nature === 'OTROS') {
            this.renderOtrosHybridBlock();
        }
    }

    renderOtrosHybridBlock() {
        const container = this.dynamicBlocksContainerTarget;
        container.innerHTML = `
            <div class="card shadow mb-4 border-0" style="border-top: 4px solid #f6c23e;">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning text-uppercase" style="font-size: 0.75rem;">Módulos Adicionales (Modo Híbrido)</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox bg-light p-3 rounded border">
                                <input type="checkbox" class="custom-control-input" id="checkTrazabilidad" data-action="change->material-refactored-form#toggleOtrosSections">
                                <label class="custom-control-label font-weight-bold text-primary" for="checkTrazabilidad">Necesita Trazabilidad (Lotes/Caducidad)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="custom-control custom-checkbox bg-light p-3 rounded border">
                                <input type="checkbox" class="custom-control-input" id="checkActivo" data-action="change->material-refactored-form#toggleOtrosSections">
                                <label class="custom-control-label font-weight-bold text-primary" for="checkActivo">Es un Activo Técnico (S/N / Garantía)</label>
                            </div>
                        </div>
                    </div>
                    <div id="sectionTrazabilidad" class="d-none mt-3 p-3 bg-white rounded border shadow-sm" style="border-left: 4px solid #e74a3b !important;">
                        <h6 class="text-danger font-weight-bold text-xs mb-3 text-uppercase">Trazabilidad de Lote</h6>
                        <div class="row">
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">LOTE*</label><input type="text" name="extra_data[batchNumber]" class="form-control"></div>
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">FECHA CADUCIDAD*</label><input type="date" name="extra_data[expirationDate]" class="form-control expiration-date"></div>
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">PROVEEDOR</label><input type="text" name="extra_data[supplier]" class="form-control"></div>
                        </div>
                    </div>
                    <div id="sectionActivo" class="d-none mt-3 p-3 bg-white rounded border shadow-sm" style="border-left: 4px solid #f6c23e !important;">
                        <h6 class="text-warning font-weight-bold text-xs mb-3 text-uppercase">Información del Activo</h6>
                        <div class="row">
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">NÚMERO DE SERIE (S/N)</label><input type="text" name="extra_data[serialNumber]" class="form-control"></div>
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">MARCA/MODELO</label><input type="text" name="extra_data[brandModel]" class="form-control"></div>
                            <div class="col-md-4"><label class="text-primary text-xs font-weight-bold">F. GARANTÍA</label><input type="date" name="extra_data[warrantyDate]" class="form-control"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    toggleOtrosSections() {
        const checkTrazabilidad = this.element.querySelector('#checkTrazabilidad').checked;
        const checkActivo = this.element.querySelector('#checkActivo').checked;
        this.element.querySelector('#sectionTrazabilidad').classList.toggle('d-none', !checkTrazabilidad);
        this.element.querySelector('#sectionActivo').classList.toggle('d-none', !checkActivo);
    }

    handleAddButtonClick() {
        const natureSelect = this.element.querySelector('select[name*="[nature]"]');
        if (!natureSelect) return;
        const nature = natureSelect.value;
        if (nature === 'CONSUMIBLE') {
            this.addBatchRow();
        } else if (nature === 'EQUIPO_TECNICO') {
            this.addUnitRow();
        }
    }

    addBatchRow(data = null) {
        const template = document.getElementById('tpl-batch');
        if (!template) {
            console.error("Template tpl-batch not found");
            return;
        }
        const index = this.dynamicBlocksContainerTarget.querySelectorAll('.batch-block').length;
        const html = template.innerHTML.replace(/__INDEX__/g, index);
        const div = document.createElement('div');
        div.innerHTML = html;
        const row = div.firstElementChild;
        if (data) {
            row.querySelector('[name*="[id]"]').value = data.id || '';
            row.querySelector('[name*="[batchNumber]"]').value = data.batchNumber || '';
            row.querySelector('[name*="[expirationDate]"]').value = data.expirationDate || '';
            row.querySelector('[name*="[supplier]"]').value = data.supplier || '';
            row.querySelector('[name*="[unitsPerPackage]"]').value = data.unitsPerPackage || '1';
            row.querySelector('[name*="[numPackages]"]').value = data.numPackages || '1';
            row.querySelector('[name*="[totalPrice]"]').value = data.totalPrice || '0,00';
            row.querySelector('[name*="[marginPercentage]"]').value = data.marginPercentage || '0,00';
            row.querySelector('[name*="[iva]"]').value = data.iva || '21';
        }
        this.dynamicBlocksContainerTarget.appendChild(row);
        this.recalculate();
    }

    addUnitRow(data = null) {
        const template = document.getElementById('tpl-unit');
        if (!template) {
            console.error("Template tpl-unit not found");
            return;
        }
        const index = this.dynamicBlocksContainerTarget.querySelectorAll('.unit-block').length;
        const html = template.innerHTML.replace(/__INDEX__/g, index);
        const div = document.createElement('div');
        div.innerHTML = html;
        const row = div.firstElementChild;
        if (data) {
            row.querySelector('[name*="[id]"]').value = data.id || '';
            row.querySelector('[name*="[alias]"]').value = data.alias || '';
            row.querySelector('[name*="[barcode]"]').value = data.barcode || '';
            row.querySelector('[name*="[serialNumber]"]').value = data.serialNumber || '';
            row.querySelector('[name*="[brandModel]"]').value = data.brandModel || '';
            row.querySelector('[name*="[swVersion]"]').value = data.swVersion || '';
            row.querySelector('[name*="[supplier]"]').value = data.supplier || '';
            row.querySelector('[name*="[purchaseDate]"]').value = data.purchaseDate || '';
            row.querySelector('[name*="[warrantyDate]"]').value = data.warrantyDate || '';
            row.querySelector('[name*="[operationalStatus]"]').value = data.operationalStatus || 'OPERATIVO';
            row.querySelector('[name*="[purchasePrice]"]').value = data.purchasePrice || '0,00';
            row.querySelector('[name*="[discountPct]"]').value = data.discountPct || '0';
            row.querySelector('[name*="[iva]"]').value = data.iva || '21';
            row.querySelector('[name*="[description]"]').value = data.description || '';
        }
        this.dynamicBlocksContainerTarget.appendChild(row);
        this.recalculate();
    }

    recalculate() {
        let totalPackages = 0;
        let totalStock = 0;
        let totalCost = 0;
        this.element.querySelectorAll('.batch-block, .unit-block').forEach(block => {
            const blockTotalRaw = block.querySelector('.block-total-price')?.value || '0';
            const blockTotal = parseFloat(blockTotalRaw.replace(',', '.')) || 0;
            const blockMarginRaw = block.querySelector('.block-margin')?.value || '0';
            const blockMargin = parseFloat(blockMarginRaw.replace(',', '.')) || 0;
            const blockIvaRaw = block.querySelector('.block-iva')?.value || '21';
            const blockIva = parseFloat(blockIvaRaw.replace(',', '.')) || 21;
            const unitsPerPackage = parseInt(block.querySelector('.block-stock')?.value || '1');
            const numPackages = parseInt(block.querySelector('.block-num-packages')?.value || '1');
            const totalUnits = unitsPerPackage * numPackages;
            totalPackages += numPackages;
            totalStock += totalUnits;
            totalCost += blockTotal;
            if (totalUnits > 0) {
                const bPrecioCompra = blockTotal / totalUnits;
                const bPUd = bPrecioCompra * (1 + (blockMargin / 100)) * (1 + (blockIva / 100));
                const pUdInput = block.querySelector('.block-unit-price');
                if (pUdInput) pUdInput.value = bPUd.toFixed(2).replace('.', ',');
            }
        });
        const sidebarTotalPackages = this.element.querySelector('[name="total_envases"]');
        if (sidebarTotalPackages) sidebarTotalPackages.value = totalPackages;
        const sidebarTotalStock = this.element.querySelector('.h5.text-gray-800');
        if (sidebarTotalStock) sidebarTotalStock.textContent = totalStock;
        const sidebarTotalCost = this.element.querySelector('[name="total_coste"]');
        if (sidebarTotalCost) sidebarTotalCost.value = totalCost.toFixed(2).replace('.', ',');
    }
}
