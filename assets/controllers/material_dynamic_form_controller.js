import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['unitsContainer', 'totalStockInput', 'totalPrice', 'unitPrice', 'unitsPerPackageInput', 'numPackagesInput', 'natureSelect', 'technicalBlock', 'technicalBlocksContainer', 'consumableBlock', 'discountPercentageInput', 'discountedPriceInput', 'unitsPerPackageContainer', 'barcodeInput', 'serialNumberInput', 'batchesContainer', 'addBatchBtnContainer', 'stockAndCostsBlock', 'headerAddBtnContainer', 'subFamilySelect', 'numPackagesContainer', 'safetyStockContainer', 'totalPriceContainer', 'discountPercentageContainer', 'discountedPriceContainer', 'unitPriceContainer', 'ivaContainer'];

    connect() {
        this.initialUnits = JSON.parse(this.element.dataset.initialUnits || '[]');
        this.initialBatches = JSON.parse(this.element.dataset.initialBatches || '[]');

        // On edit mode, sync numPackages input with the real unit count
        // so that generateTechnicalBlocks() creates the correct number of blocks.
        const initialCount = parseInt(this.element.dataset.initialUnitCount || '0');
        if (initialCount > 0 && this.hasNumPackagesInputTarget) {
            this.numPackagesInputTarget.value = String(initialCount);
        }

        this.setupCategorySpecifics();
        this.toggleTechnicalBlock();
        this.performCalculations();
        this.setupValidation();
        this.initAutoWidth();
        this.updateHeaderAddButton();
        this.customizeBlockD();
    }

    setupCategorySpecifics() {
        const category = this.element.dataset.materialCategory;
        if (category === 'Comunicaciones' && this.hasNatureSelectTarget) {
            // Requirement: Change "Consumible" to "Accesorios"
            Array.from(this.natureSelectTarget.options).forEach(option => {
                if (option.value === 'CONSUMIBLE') {
                    option.text = 'Accesorios';
                }
            });
        }
    }

    initAutoWidth() {
        const inputs = this.element.querySelectorAll('input[type="text"]:not([name*="[name]"]), input[type="number"], select, textarea');
        inputs.forEach(input => {
            if (input.tagName === 'TEXTAREA') {
                this.adjustTextareaHeight(input);
                input.addEventListener('input', () => this.adjustTextareaHeight(input));
            } else {
                this.adjustInputWidth(input);
                input.addEventListener('input', () => this.adjustInputWidth(input));
                input.addEventListener('change', () => this.adjustInputWidth(input));
            }
        });
    }

    adjustInputWidth(input) {
        if (input.tagName === 'SELECT') {
            const temp = document.createElement('span');
            temp.style.visibility = 'hidden';
            temp.style.position = 'absolute';
            temp.style.whiteSpace = 'pre';
            temp.style.font = window.getComputedStyle(input).font;
            temp.innerText = input.options[input.selectedIndex]?.text || '';
            document.body.appendChild(temp);
            input.style.width = (temp.getBoundingClientRect().width + 40) + 'px';
            document.body.removeChild(temp);
        } else {
            const value = input.value || input.placeholder || '';
            const temp = document.createElement('span');
            temp.style.visibility = 'hidden';
            temp.style.position = 'absolute';
            temp.style.whiteSpace = 'pre';
            temp.style.font = window.getComputedStyle(input).font;
            temp.innerText = value;
            document.body.appendChild(temp);
            const width = temp.getBoundingClientRect().width;

            // Requirement: Barcode default width for 13 chars
            let minWidth = 50;
            if (input.name && input.name.includes('[barcode]')) {
                // Approximate width for 13 chars (approx 120px depending on font)
                minWidth = 140;
            }

            input.style.width = Math.max(width + 30, minWidth) + 'px';
            document.body.removeChild(temp);
        }
    }

    adjustTextareaHeight(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = (textarea.scrollHeight) + 'px';
    }

    enforceNumericConstraints(event) {
        let field = event.target;
        let value = field.value;
        const isDecimal = field.dataset.type === 'decimal' ||
            field.dataset.materialDynamicFormTarget === 'discountPercentageInput' ||
            field.dataset.materialDynamicFormTarget === 'totalPrice' ||
            field.name?.includes('discountPercentage');

        if (isDecimal) {
            // Remove anything not digit, comma or dot
            value = value.replace(/[^0-9.,]/g, '');

            // Allow multiple dots (thousands) and one comma (decimal)
            // But if there's ONLY dots and no comma, we handle that in formatInput (Smart Parsing)
            const commaIndex = value.indexOf(',');
            if (commaIndex !== -1) {
                value = value.substring(0, commaIndex + 1) + value.substring(commaIndex + 1).replace(/,/g, '');
            }

            // Leading zero logic
            if (value.startsWith('0') && value.length > 1 && value[1] !== ',' && value[1] !== '.') {
                value = value.replace(/^0+/, '');
            }
            if (value.startsWith(',') || value.startsWith('.')) {
                value = '0' + value;
            }
        } else {
            // Integer fields: only digits
            value = value.replace(/[^0-9]/g, '');
            // No leading zeros
            if (value.length > 1 && value.startsWith('0')) {
                value = value.replace(/^0+/, '');
            }
        }

        if (field.value !== value) {
            field.value = value;
        }
    }

    formatInput(event) {
        const field = event.target;
        let value = field.value;
        if (!value) return;

        const isDecimal = field.dataset.type === 'decimal' ||
            field.dataset.materialDynamicFormTarget === 'discountPercentageInput' ||
            field.dataset.materialDynamicFormTarget === 'totalPrice' ||
            field.name?.includes('discountPercentage');

        if (isDecimal) {
            // Smart Parsing: Convert dot to comma if it looks like a decimal separator
            const dotCount = (value.match(/\./g) || []).length;
            const commaCount = (value.match(/,/g) || []).length;

            if (commaCount === 0 && dotCount === 1) {
                // Single dot case: Always treat as decimal (e.g. 12.40 -> 12,40)
                value = value.replace('.', ',');
            } else if (commaCount === 0 && dotCount > 1) {
                // Multiple dots case: The LAST dot might be a decimal if there's no comma
                // User requirement: "si es 1.234.52 se pondra 1.234,52"
                const lastDotIndex = value.lastIndexOf('.');
                const before = value.substring(0, lastDotIndex);
                const after = value.substring(lastDotIndex + 1);
                value = before + ',' + after;
            }
        }

        const numericValue = this.parseFormattedNumber(value);
        field.value = this.formatToUserLocale(numericValue, isDecimal ? 2 : 0);

        // Trigger calculation if needed
        this.performCalculations();
    }

    // Helper to parse strings like "1.000,05" to float
    parseFormattedNumber(value) {
        if (!value) return 0;
        let str = value.toString();
        // Remove thousands separators (dots)
        str = str.replace(/\./g, '');
        // Change decimal separator (comma) to dot
        str = str.replace(/,/g, '.');
        return parseFloat(str) || 0;
    }

    // Helper to format float to "1.000,05"
    formatToUserLocale(value, decimals = 2) {
        if (isNaN(value) || value === null || value === undefined) return decimals > 0 ? '0,00' : '0';
        return new Intl.NumberFormat('es-ES', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
            useGrouping: true
        }).format(value);
    }

    setupValidation() {
        const form = this.element.querySelector('form') || this.element.closest('form');
        if (form) {
            form.addEventListener('submit', (e) => this.validateForm(e));
        }

        // Attach listeners to initial required fields
        this.element.querySelectorAll('[data-required="true"]').forEach(input => {
            this.attachValidationListeners(input);
        });

        // MutationObserver to watch for dynamic fields (Bloque C)
        const observer = new MutationObserver(() => {
            this.element.querySelectorAll('[data-required="true"]:not([data-validated])').forEach(input => {
                this.attachValidationListeners(input);
            });
        });
        observer.observe(this.element, { childList: true, subtree: true });
    }

    attachValidationListeners(input) {
        if (input.dataset.validated) return;

        input.addEventListener('input', () => {
            if (input.value.trim() !== '') {
                this.clearError(input);
                input.classList.add('input-success');
            } else {
                input.classList.remove('input-success');
            }
        });

        input.addEventListener('blur', () => {
            if (input.value.trim() === '') {
                this.showError(input);
            }
        });

        input.dataset.validated = 'true';
    }

    validateForm(event) {
        let hasErrors = false;
        const requiredFields = this.element.querySelectorAll('[data-required="true"]');

        // Only validate visible fields
        requiredFields.forEach(field => {
            const isVisible = !!(field.offsetWidth || field.offsetHeight || field.getClientRects().length);
            if (!isVisible) return;

            if (!field.value || field.value.trim() === '') {
                this.showError(field);
                hasErrors = true;
            }
        });

        if (this.hasBarcodeInputTarget && this.barcodeInputTarget.dataset.barcodeExists === 'true') {
            this.showError(this.barcodeInputTarget, 'Este Código de Barras ya está registrado.');
            hasErrors = true;
        }

        if (this.hasSerialNumberInputTarget || this.hasSerialNumberInputTargets) {
            const snInputs = this.hasSerialNumberInputTarget ? [this.serialNumberInputTarget] : this.serialNumberInputTargets;
            snInputs.forEach(target => {
                if (target.dataset.serialExists === 'true') {
                    this.showError(target, 'Este Número de serie ya está registrado.');
                    hasErrors = true;
                }
            });
        }

        if (hasErrors) {
            event.preventDefault();
            event.stopPropagation();

            // Visual feedback on submit button
            const submitBtn = event.submitter || this.element.querySelector('button[type="submit"]');
            if (submitBtn) {
                const originalText = submitBtn.innerHTML;
                submitBtn.classList.add('btn-danger');
                submitBtn.classList.remove('btn-primary');
                submitBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i> Revisa los campos';

                setTimeout(() => {
                    submitBtn.classList.remove('btn-danger');
                    submitBtn.classList.add('btn-primary');
                    submitBtn.innerHTML = originalText;
                }, 3000);
            }
        }
    }

    showError(field, msg = 'Este campo es obligatorio') {
        field.classList.remove('input-success');
        field.classList.add('input-error', 'shake');

        // Remove shake after animation
        setTimeout(() => field.classList.remove('shake'), 400);

        // Fixed message logic
        let message = field.parentNode.querySelector('.field-message');
        if (!message) {
            message = document.createElement('div');
            message.className = 'field-message error';
            field.parentNode.appendChild(message);
        }
        message.innerText = msg;
        message.classList.add('show');
    }

    clearError(field) {
        field.classList.remove('input-error');
        const message = field.parentNode.querySelector('.field-message');
        if (message) {
            message.classList.remove('show');
        }
    }

    performCalculations(event = null) {
        if (event && event.target && event.target.name && event.target.name.includes('batches_data')) {
            this.calculateBatchCosts(event.target);
            this.calculateTotalStockFromBatches();
        } else {
            this.calculateStock();
            this.calculateCosts();
        }
        this.updateDynamicBlocks();
    }

    updateDynamicBlocks() {
        if (!this.hasNatureSelectTarget) return;

        const nature = this.natureSelectTarget.value;
        const totalStock = parseInt(this.totalStockInputTarget?.value) || 0;
        const numPackages = parseInt(this.numPackagesInputTarget?.value) || 0;

        if (nature === 'EQUIPO_TECNICO') {
            this.generateTechnicalBlocks(numPackages);
        } else if (this.element.dataset.materialCategory === 'Comunicaciones') {
            this.generateUnitFields(totalStock);
        }
    }

    calculateStock() {
        const nature = this.hasNatureSelectTarget ? this.natureSelectTarget.value : 'CONSUMIBLE';

        if ((nature === 'CONSUMIBLE' || nature === 'OTROS') && this.hasBatchesContainerTarget && this.batchesContainerTarget.children.length > 0) {
            this.calculateTotalStockFromBatches();
            return;
        }

        if (!this.hasUnitsPerPackageInputTarget || !this.hasNumPackagesInputTarget || !this.hasTotalStockInputTarget) {
            return;
        }

        // Skip auto-calculation if category is mobility
        if (this.element.dataset.materialCategory === 'Movilidad') return;

        const unitsVal = this.unitsPerPackageInputTarget.value;
        const packagesVal = this.numPackagesInputTarget.value;

        const unitsPerPackage = this.parseFormattedNumber(unitsVal);
        const numPackages = this.parseFormattedNumber(packagesVal);
        const totalStock = Math.round(unitsPerPackage * numPackages);

        this.totalStockInputTarget.value = this.formatToUserLocale(totalStock, 0);

        // Trigger unit fields generation if needed
        if (this.element.dataset.materialCategory === 'Comunicaciones') {
            this.generateUnitFields(totalStock);
        }
    }

    calculateCosts() {
        if (!this.hasTotalPriceTarget || !this.hasUnitPriceTarget || !this.hasTotalStockInputTarget) return;

        const totalCost = this.parseFormattedNumber(this.totalPriceTarget.value);
        const stock = this.parseFormattedNumber(this.totalStockInputTarget.value);

        const discount = this.hasDiscountPercentageInputTarget ? this.parseFormattedNumber(this.discountPercentageInputTarget.value) : 0;

        let discountedTotal = totalCost;
        if (discount > 0 && discount <= 100) {
            discountedTotal = totalCost - (totalCost * (discount / 100));
        }

        if (this.hasDiscountedPriceInputTarget) {
            this.discountedPriceInputTarget.value = this.formatToUserLocale(discountedTotal);
        }

        if (stock > 0) {
            this.unitPriceTarget.value = this.formatToUserLocale(discountedTotal / stock);
        } else {
            this.unitPriceTarget.value = '0,00';
        }
    }

    handleStockChange() {
        this.calculateCosts();
        // If it's a manual stock change (not Sanitario), we might still need units
        if (this.hasTotalStockInputTarget && this.element.dataset.materialCategory === 'Comunicaciones') {
            this.generateUnitFields(parseInt(this.totalStockInputTarget.value) || 0);
        }
    }

    calculateStockSanitario() {
        this.performCalculations();
    }

    toggleTechnicalBlock() {
        if (!this.hasNatureSelectTarget) return;

        const nature = this.natureSelectTarget.value;
        const category = this.element.dataset.materialCategory;

        const isTechnical = nature === 'EQUIPO_TECNICO';
        const isConsumable = nature === 'CONSUMIBLE';
        const isOther = nature === 'OTROS';

        if (this.hasTechnicalBlockTarget) {
            this.technicalBlockTarget.classList.add('d-none');
        }

        if (this.hasConsumableBlockTarget) {
            this.consumableBlockTarget.classList.add('d-none');
        }

        if (this.hasTechnicalBlocksContainerTarget) {
            this.technicalBlocksContainerTarget.classList.toggle('d-none', !isTechnical);
            if (isTechnical) {
                this.generateTechnicalBlocks(parseInt(this.numPackagesInputTarget?.value) || 0);
            }
        }

        if (this.hasBatchesContainerTarget) {
            this.batchesContainerTarget.classList.toggle('d-none', !isConsumable && !isOther);
            if ((isConsumable || isOther) && this.batchesContainerTarget.children.length === 0) {
                if (this.initialBatches && this.initialBatches.length > 0) {
                    this.initialBatches.forEach(batch => this.addBatchRow(null, batch));
                } else {
                    this.addBatchRow();
                }
            }
        }

        if (this.hasAddBatchBtnContainerTarget) {
            this.addBatchBtnContainerTarget.classList.toggle('d-none', !isConsumable && !isOther);
        }

        if (this.hasStockAndCostsBlockTarget) {
            this.stockAndCostsBlockTarget.classList.remove('d-none');

            // Toggle specific fields that are redundant in multi-batch
            const isMultiBatchActive = this.hasBatchesContainerTarget && this.batchesContainerTarget.children.length > 0;
            const redundantFields = this.stockAndCostsBlockTarget.querySelectorAll('[data-redundant-multi-batch="true"]');
            redundantFields.forEach(field => {
                const isTotalField = field.dataset.materialDynamicFormTarget === 'numPackagesContainer' ||
                                     field.dataset.materialDynamicFormTarget === 'totalPriceContainer';

                if (isTotalField && isMultiBatchActive && (isConsumable || isOther)) {
                    field.classList.remove('d-none');
                    const input = field.querySelector('input');
                    if (input) {
                        input.readOnly = true;
                        input.classList.add('bg-gray-50', 'cursor-default');
                    }
                } else {
                    field.classList.toggle('d-none', (isConsumable || isOther) && isMultiBatchActive);
                    const input = field.querySelector('input');
                    if (input && !isMultiBatchActive) {
                         input.readOnly = false;
                         input.classList.remove('bg-gray-50', 'cursor-default');
                    }
                }
            });
        }

        this.handleNatureChange();
        this.updateHeaderAddButton();
        this.customizeBlockD();
    }

    updateHeaderAddButton() {
        if (!this.hasHeaderAddBtnContainerTarget) return;
        const nature = this.natureSelectTarget?.value;
        const category = this.element.dataset.materialCategory;
        const show = nature === 'EQUIPO_TECNICO' || nature === 'CONSUMIBLE' || nature === 'OTROS';
        this.headerAddBtnContainerTarget.classList.toggle('d-none', !show);
    }

    customizeBlockD() {
        const category = this.element.dataset.materialCategory;
        const nature = this.natureSelectTarget?.value;
        const isSanitarioOrComms = category === 'Sanitario' || category === 'Comunicaciones';
        const isOther = nature === 'OTROS' || (category === 'Sanitario' && nature === 'OTROS');

        if (isSanitarioOrComms || isOther) {
            if (this.hasUnitsPerPackageContainerTarget) {
                this.unitsPerPackageContainerTarget.style.setProperty('display', 'none', 'important');
                const input = this.unitsPerPackageContainerTarget.querySelector('input');
                if (input && (input.value === '' || input.value === '0')) {
                    input.value = '1';
                }
            }
            if (this.hasNumPackagesContainerTarget) this.numPackagesContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasTotalPriceContainerTarget) this.totalPriceContainerTarget.style.setProperty('display', 'block', 'important');

            // Hide others for specific view
            if (this.hasSafetyStockContainerTarget) this.safetyStockContainerTarget.style.setProperty('display', 'none', 'important');
            if (this.hasDiscountPercentageContainerTarget) this.discountPercentageContainerTarget.style.setProperty('display', 'none', 'important');
            if (this.hasDiscountedPriceContainerTarget) this.discountedPriceContainerTarget.style.setProperty('display', 'none', 'important');
            if (this.hasUnitPriceContainerTarget) this.unitPriceContainerTarget.style.setProperty('display', 'none', 'important');
            if (this.hasIvaContainerTarget) this.ivaContainerTarget.style.setProperty('display', 'none', 'important');

            // Specific requirement for "Otros": price and numPackages are mandatory
            if (isOther) {
                const priceInput = this.totalPriceContainerTarget?.querySelector('input');
                const numInput = this.numPackagesContainerTarget?.querySelector('input');
                if (priceInput) priceInput.dataset.required = "true";
                if (numInput) numInput.dataset.required = "true";
            }
        } else {
            // Restore visibility for other categories
            if (this.hasUnitsPerPackageContainerTarget) this.unitsPerPackageContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasSafetyStockContainerTarget) this.safetyStockContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasDiscountPercentageContainerTarget) this.discountPercentageContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasDiscountedPriceContainerTarget) this.discountedPriceContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasUnitPriceContainerTarget) this.unitPriceContainerTarget.style.setProperty('display', 'block', 'important');
            if (this.hasIvaContainerTarget) this.ivaContainerTarget.style.setProperty('display', 'block', 'important');
        }
    }

    async saveNewSubFamily() {
        const nameInput = document.getElementById('new-subfamily-name');
        const name = nameInput.value.trim();
        if (!name) return;

        try {
            const response = await fetch('/material/subfamily/new', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name: name })
            });

            if (!response.ok) throw new Error('Error al guardar la subfamilia');

            const option = document.createElement('option');
            option.value = name;
            option.text = name;
            this.subFamilySelectTarget.add(option);
            this.subFamilySelectTarget.value = name;

            // Close modal
            const modalElement = document.getElementById('addSubFamilyModal');
            let modalInstance;
            if (window.bootstrap) {
                modalInstance = window.bootstrap.Modal.getInstance(modalElement) || new window.bootstrap.Modal(modalElement);
            } else if (typeof bootstrap !== 'undefined') {
                modalInstance = bootstrap.Modal.getInstance(modalElement) || new bootstrap.Modal(modalElement);
            }

            if (modalInstance) {
                modalInstance.hide();
            }

            nameInput.value = '';
            this.adjustInputWidth(this.subFamilySelectTarget);
        } catch (error) {
            console.error('Error saving subfamily:', error);
            alert('No se pudo guardar la subfamilia. Inténtalo de nuevo.');
        }
    }

    handleNatureChange() {
        if (!this.hasNatureSelectTarget) return;
        const nature = this.natureSelectTarget.value;
        const category = this.element.dataset.materialCategory;
        const isTechnical = nature === 'EQUIPO_TECNICO';

        // Requirement: Hide "Tipo de tallaje" for Sanitario and Communications
        const sizingContainer = document.getElementById('sizing-type-container') || this.element.querySelector('[name*="[sizingType]"]')?.closest('.col-md-6');
        if (sizingContainer) {
            const hideSizing = category === 'Sanitario' || category === 'Comunicaciones';
            sizingContainer.classList.toggle('d-none', hideSizing);
        }

        // Requirement: Hide Expiration Date for Communications if not consumable (Accesorios)
        if (category === 'Comunicaciones') {
            const expirationInput = this.element.querySelector('[name*="[expirationDate]"]');
            if (expirationInput) {
                expirationInput.closest('.col-md-6')?.classList.toggle('d-none', nature !== 'CONSUMIBLE');
            }

            // Toggle visibility of fields based on Nature/Equipment Type
            this.toggleCommsFields(nature);
        }

        // Toggle units per package visibility
        if (this.hasUnitsPerPackageContainerTarget) {
            this.unitsPerPackageContainerTarget.classList.toggle('d-none', isTechnical);

            const input = this.unitsPerPackageContainerTarget.querySelector('input');
            if (isTechnical && input) {
                input.value = '1'; // Default for technical
                this.clearError(input);
            }
        }

        // Default numPackages to 1 for technical, but only if not already set (e.g., from initial unit count in edit mode)
        if (isTechnical && this.hasNumPackagesInputTarget) {
            const currentVal = parseInt(this.numPackagesInputTarget.value) || 0;
            if (currentVal < 1) {
                this.numPackagesInputTarget.value = '1';
            }
            this.clearError(this.numPackagesInputTarget);
            this.performCalculations();
        }
    }

    toggleCommsFields(nature) {
        const isAccessories = nature === 'CONSUMIBLE';

        // Hide Panel B (Logical Data) if it's Accessories
        const panelB = this.element.querySelector('.border-left-info');
        if (panelB) {
            if (isAccessories) {
                panelB.style.setProperty('display', 'none', 'important');
            } else {
                panelB.style.setProperty('display', 'block', 'important');
            }
        }
    }

    handleDateChange(event) {
        if (event.target.name.includes('[purchaseDate]')) {
            const purchaseDateStr = event.target.value;
            if (!purchaseDateStr) return;

            const purchaseDate = new Date(purchaseDateStr);
            if (isNaN(purchaseDate.getTime())) return;

            const warrantyDate = new Date(purchaseDate);
            warrantyDate.setFullYear(warrantyDate.getFullYear() + 3);

            const warrantyInput = event.target.closest('.card-body').querySelector('input[name*="[warrantyEndDate]"]');
            if (warrantyInput) {
                warrantyInput.value = warrantyDate.toISOString().split('T')[0];
            }
        }
    }

    generateTechnicalBlocks(count) {
        if (!this.hasTechnicalBlocksContainerTarget) return;

        const isEdit = this.element.dataset.materialAction === 'edit';
        const isTechnical = this.natureSelectTarget.value === 'EQUIPO_TECNICO';
        const defaultBrand = this.element.dataset.materialBrand || '';
        const defaultPurchase = this.element.dataset.materialPurchase || '';
        const defaultWarranty = this.element.dataset.materialWarranty || '';

        if (count > 50) count = 50; // Safety limit

        const currentData = {};

        // If it's the first time and we have initialUnits, use them
        if (this.technicalBlocksContainerTarget.children.length === 0 && this.initialUnits.length > 0) {
            this.initialUnits.forEach((unit, i) => {
                currentData[`units_data[${i}][alias]`] = unit.alias;
                currentData[`units_data[${i}][serialNumber]`] = unit.serialNumber;
                currentData[`units_data[${i}][brandModel]`] = unit.brandModel;
                currentData[`units_data[${i}][purchaseDate]`] = unit.purchaseDate;
                currentData[`units_data[${i}][warrantyEndDate]`] = unit.warrantyEndDate;
                currentData[`units_data[${i}][operationalStatus]`] = unit.operationalStatus;
                currentData[`units_data[${i}][purchasePrice]`] = this.formatToUserLocale(unit.purchasePrice);
                currentData[`units_data[${i}][discountPct]`] = this.formatToUserLocale(unit.discountPct);
                currentData[`units_data[${i}][history]`] = unit.history;
            });
        }

        this.technicalBlocksContainerTarget.querySelectorAll('input, select').forEach(input => {
            currentData[input.name] = input.value;
        });

        let html = '';
        for (let i = 0; i < count; i++) {
            const aliasName = `units_data[${i}][alias]`;
            const snName = `units_data[${i}][serialNumber]`;
            const brandName = `units_data[${i}][brandModel]`;
            const purchaseName = `units_data[${i}][purchaseDate]`;
            const warrantyName = `units_data[${i}][warrantyEndDate]`;
            const statusName = `units_data[${i}][operationalStatus]`;
            const priceName = `units_data[${i}][purchasePrice]`;
            const discountName = `units_data[${i}][discountPct]`;

            html += `
                <div class="card shadow-sm mb-4 border-0" style="border-top: 4px solid #f6c23e !important;">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-warning text-uppercase">
                            UNIDAD ${i + 1}${count > 1 ? ' de ' + count : ''}: Datos Técnicos
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Alias / Identificador</label>
                                <input type="text" name="${aliasName}" value="${currentData[aliasName] || ''}" class="form-input" placeholder="Ej: Unidad 01">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Número de Serie (S/N)<span class="text-red-500">*</span></label>
                                <input type="text" name="${snName}" value="${currentData[snName] || ''}" class="form-input" placeholder="Identificador único" data-required="true"
                                    data-material-dynamic-form-target="serialNumberInput"
                                    data-action="input->material-dynamic-form#checkSerialNumberUniqueness"
                                    data-check-url="${this.element.dataset.serialCheckUrl}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Marca y Modelo<span class="text-red-500">*</span></label>
                                <input type="text" name="${brandName}" value="${currentData[brandName] || defaultBrand}" class="form-input" data-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fecha de Compra<span class="text-red-500">*</span></label>
                                <input type="date" name="${purchaseName}" value="${currentData[purchaseName] || defaultPurchase}" 
                                    data-action="change->material-dynamic-form#handleDateChange"
                                    class="form-input" data-required="true">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fin de Garantía<span class="text-red-500">*</span></label>
                                <input type="date" name="${warrantyName}" value="${currentData[warrantyName] || defaultWarranty}" class="form-input" data-required="true">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Estado Operativo</label>
                                <select name="${statusName}" class="form-input">
                                    <option value="OPERATIVO" ${(currentData[statusName] || 'OPERATIVO') === 'OPERATIVO' ? 'selected' : ''}>OPERATIVO</option>
                                    <option value="AVERIADO" ${currentData[statusName] === 'AVERIADO' ? 'selected' : ''}>AVERIADO</option>
                                    <option value="REPARACION" ${currentData[statusName] === 'REPARACION' ? 'selected' : ''}>EN REPARACIÓN</option>
                                    <option value="BAJA" ${currentData[statusName] === 'BAJA' ? 'selected' : ''}>BAJA</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Precio de Compra (IVA inc.)</label>
                                <div class="input-group">
                                    <input type="text" name="${priceName}" value="${currentData[priceName] || ''}" class="form-input" placeholder="0,00"
                                        data-type="decimal" data-action="input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">% Margen</label>
                                <div class="input-group">
                                    <input type="text" name="${discountName}" value="${currentData[discountName] || ''}" class="form-input" placeholder="0"
                                        data-type="decimal" data-action="input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        ${isEdit ? `
                        <div class="row mt-2">
                            <div class="col-12 mb-2">
                                <label class="form-label text-muted small">Motivo del cambio de estado (opcional)</label>
                                <textarea name="units_data[${i}][statusReason]" class="form-input" rows="2" 
                                    placeholder="Describe el motivo si cambias el estado operativo..."></textarea>
                            </div>
                        </div>
                        ${(currentData['history'] && currentData['history'].length > 0) ? `
                        <div class="mt-3">
                            <div class="text-xs font-weight-bold text-uppercase text-muted mb-2">
                                <i class="fas fa-history mr-1"></i> Historial de estados
                            </div>
                            <table class="table table-sm table-bordered mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="small">Fecha</th>
                                        <th class="small">Estado</th>
                                        <th class="small">Usuario</th>
                                        <th class="small">Motivo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    ${currentData['history'].map(h => `
                                        <tr>
                                            <td class="small">${h.date || '-'}</td>
                                            <td><span class="badge ${h.status === 'OPERATIVO' ? 'bg-success' : 'bg-warning text-dark'}">${h.status}</span></td>
                                            <td class="small">${h.user || 'Sistema'}</td>
                                            <td class="small text-muted">${h.reason || '-'}</td>
                                        </tr>
                                    `).join('')}
                                </tbody>
                            </table>
                        </div>` : ''}
                        ` : ''}
                    </div>
                </div>`;
        }

        this.technicalBlocksContainerTarget.innerHTML = html;
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

        // If it's the first time and we have initialUnits, use them
        if (this.unitsContainerTarget.children.length === 0 && this.initialUnits.length > 0) {
            this.initialUnits.forEach((unit, i) => {
                currentData[`units_data[${i}][alias]`] = unit.alias;
                currentData[`units_data[${i}][serialNumber]`] = unit.serialNumber;
                currentData[`units_data[${i}][networkId]`] = unit.networkId;
                currentData[`units_data[${i}][phoneNumber]`] = unit.phoneNumber;
                currentData[`units_data[${i}][purchasePrice]` ] = this.formatToUserLocale(unit.purchasePrice);
                currentData[`units_data[${i}][discountPct]`] = this.formatToUserLocale(unit.discountPct);
            });
        }

        this.unitsContainerTarget.querySelectorAll('input').forEach(input => {
            currentData[input.name] = input.value;
        });

        const isEdit = this.element.dataset.materialAction === 'edit';
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
                const statusName = `units_data[${i}][operationalStatus]`;
                const priceName = `units_data[${i}][purchasePrice]`;
                const discountName = `units_data[${i}][discountPct]`;

                html += `
                    <div class="unit-row p-3 mb-4 border rounded bg-light shadow-sm">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold">NOMBRE / ALIAS EN RED</label>
                                <input type="text" name="${aliasName}" value="${currentData[aliasName] || ''}" class="form-control form-control-sm" placeholder="Ej: ALFA 1">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold">Nº SERIE (S/N)</label>
                                <input type="text" name="${snName}" value="${currentData[snName] || ''}" class="form-control form-control-sm"
                                    data-material-dynamic-form-target="serialNumberInput"
                                    data-action="input->material-dynamic-form#checkSerialNumberUniqueness"
                                    data-check-url="${this.element.dataset.serialCheckUrl}">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold">ID RED (ISSI/IMEI)</label>
                                <input type="text" name="${netName}" value="${currentData[netName] || ''}" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-3 mb-2">
                                <label class="small font-weight-bold">Nº TELÉFONO</label>
                                <input type="text" name="${phoneName}" value="${currentData[phoneName] || ''}" class="form-control form-control-sm" placeholder="+34...">
                            </div>
                        </div>
                        <div class="row mt-2 border-top pt-2">
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold">ESTADO OPERATIVO</label>
                                <select name="${statusName}" class="form-control form-control-sm">
                                    <option value="OPERATIVO" ${(currentData[statusName] || 'OPERATIVO') === 'OPERATIVO' ? 'selected' : ''}>OPERATIVO</option>
                                    <option value="AVERIADO" ${currentData[statusName] === 'AVERIADO' ? 'selected' : ''}>AVERIADO</option>
                                    <option value="REPARACION" ${currentData[statusName] === 'REPARACION' ? 'selected' : ''}>EN REPARACIÓN</option>
                                    <option value="BAJA" ${currentData[statusName] === 'BAJA' ? 'selected' : ''}>BAJA</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold">PRECIO COMPRA (IVA inc.)</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="${priceName}" value="${currentData[priceName] || ''}" class="form-control" placeholder="0,00"
                                        data-type="decimal" data-action="input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label class="small font-weight-bold">% MARGEN</label>
                                <div class="input-group input-group-sm">
                                    <input type="text" name="${discountName}" value="${currentData[discountName] || ''}" class="form-control" placeholder="0"
                                        data-type="decimal" data-action="input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                        ${isEdit ? `
                        <div class="row mt-1">
                            <div class="col-12">
                                <label class="small text-muted">Motivo cambio estado (opcional)</label>
                                <input type="text" name="units_data[${i}][statusReason]" class="form-control form-control-sm" placeholder="Describe el motivo si cambias el estado...">
                            </div>
                        </div>
                        ${(currentData['history'] && currentData['history'].length > 0) ? `
                        <div class="mt-2 small">
                            <div class="font-weight-bold text-muted mb-1 text-uppercase" style="font-size: 0.65rem;">Historial de estados:</div>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0" style="font-size: 0.7rem;">
                                    <tbody>
                                        ${currentData['history'].map(h => `
                                            <tr>
                                                <td width="25%">${h.date || '-'}</td>
                                                <td width="20%"><span class="badge ${h.status === 'OPERATIVO' ? 'bg-success' : 'bg-warning text-dark'}" style="font-size: 0.6rem;">${h.status}</span></td>
                                                <td width="20%">${h.user || 'Sistema'}</td>
                                                <td>${h.reason || '-'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>` : ''}
                        ` : ''}
                    </div>`;
            }
            html += `</div></div>`;
        }
        this.unitsContainerTarget.innerHTML = html;
    }


    calculateUnitPrice() {
        this.calculateCosts();
    }

    handleIvaChange() {
        this.performCalculations();
    }

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    handleAddButton(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        const nature = this.natureSelectTarget?.value;
        if (nature === 'EQUIPO_TECNICO') {
            if (this.hasNumPackagesInputTarget) {
                const currentVal = parseInt(this.numPackagesInputTarget.value) || 0;
                this.numPackagesInputTarget.value = String(currentVal + 1);
                this.performCalculations();
            }
        } else {
            this.addBatchRow();
        }
    }

    addBatchRow(event = null, initialData = null) {
        if (!this.hasBatchesContainerTarget) return;

        const index = this.batchesContainerTarget.children.length;
        const div = document.createElement('div');
        div.className = 'card shadow-sm mb-4 border-0';
        div.style.borderTop = '4px solid #e74a3b !important';

        const isEdit = this.element.dataset.materialAction === 'edit';

        div.innerHTML = `
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger text-uppercase">
                    Lote ${index + 1}: Seguridad, Trazabilidad y Costes
                </h6>
                ${index > 0 ? `<button type="button" class="btn btn-sm btn-outline-danger" data-action="click->material-dynamic-form#removeBatchRow"><i class="fas fa-trash"></i></button>` : ''}
            </div>
            <div class="card-body">
                <input type="hidden" name="batches_data[${index}][id]" value="${initialData?.id || ''}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Lote<span class="text-red-500">*</span></label>
                        <input type="text" name="batches_data[${index}][batchNumber]" value="${this.escapeHtml(initialData?.batchNumber)}" class="form-input" data-required="true">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Fecha Caducidad<span class="text-red-500">*</span></label>
                        <input type="date" name="batches_data[${index}][expirationDate]" value="${initialData?.expirationDate || ''}" class="form-input" data-required="true">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Proveedor<span class="text-red-500">*</span></label>
                        <input type="text" name="batches_data[${index}][supplier]" value="${this.escapeHtml(initialData?.supplier)}" class="form-input" data-required="true">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Uds/Envase<span class="text-red-500">*</span></label>
                        <input type="text" name="batches_data[${index}][unitsPerPackage]" value="${this.formatToUserLocale(initialData?.unitsPerPackage, 0) || ''}" class="form-input" data-required="true"
                            data-action="input->material-dynamic-form#performCalculations input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Nº Envases<span class="text-red-500">*</span></label>
                        <input type="text" name="batches_data[${index}][numPackages]" value="${this.formatToUserLocale(initialData?.numPackages, 0) || ''}" class="form-input" data-required="true"
                            data-action="input->material-dynamic-form#performCalculations input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">Precio Compra<span class="text-red-500">*</span></label>
                        <div class="input-group">
                            <input type="text" name="batches_data[${index}][totalPrice]" value="${this.formatToUserLocale(initialData?.totalPrice, 2) || ''}" class="form-input" data-required="true" data-type="decimal"
                                data-action="input->material-dynamic-form#performCalculations input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                            <span class="input-group-text p-1">€</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">% Margen</label>
                        <div class="input-group">
                            <input type="text" name="batches_data[${index}][marginPercentage]" value="${this.formatToUserLocale(initialData?.marginPercentage, 2) || ''}" class="form-input" data-type="decimal"
                                data-action="input->material-dynamic-form#performCalculations input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                            <span class="input-group-text p-1">%</span>
                        </div>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">IVA (%)</label>
                        <input type="text" name="batches_data[${index}][iva]" value="${initialData?.iva || '21'}" class="form-input"
                            data-action="input->material-dynamic-form#performCalculations input->material-dynamic-form#enforceNumericConstraints blur->material-dynamic-form#formatInput">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">P/Ud</label>
                        <div class="input-group">
                            <input type="text" name="batches_data[${index}][unitPrice]" value="${this.formatToUserLocale(initialData?.unitPrice, 2) || ''}" class="form-input bg-light font-weight-bold" readonly>
                            <span class="input-group-text p-1">€</span>
                        </div>
                    </div>
                </div>
            </div>
        `;

        this.batchesContainerTarget.appendChild(div);
        this.performCalculations();
    }

    removeBatchRow(event) {
        const row = event.target.closest('.card');
        if (row) {
            row.remove();
            this.reindexBatchRows();
            this.performCalculations();
        }
    }

    reindexBatchRows() {
        const rows = this.batchesContainerTarget.querySelectorAll('.card');
        rows.forEach((row, index) => {
            row.querySelector('h6').textContent = `Lote ${index + 1}: Seguridad, Trazabilidad y Costes`;
            row.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/batches_data\[\d+\]/, `batches_data[${index}]`);
            });
        });
    }

    calculateBatchCosts(targetInput) {
        const row = targetInput.closest('.card');
        if (!row) return;

        const unitsInput = row.querySelector('[name*="[unitsPerPackage]"]');
        const packsInput = row.querySelector('[name*="[numPackages]"]');
        const totalPriceInput = row.querySelector('[name*="[totalPrice]"]');
        const marginInput = row.querySelector('[name*="[marginPercentage]"]');
        const unitPriceInput = row.querySelector('[name*="[unitPrice]"]');

        if (!unitsInput || !packsInput || !totalPriceInput || !unitPriceInput) return;

        const unitsPerPackage = this.parseFormattedNumber(unitsInput.value);
        const numPackages = this.parseFormattedNumber(packsInput.value);
        const totalStock = unitsPerPackage * numPackages;
        const totalPrice = this.parseFormattedNumber(totalPriceInput.value);
        const margin = marginInput ? this.parseFormattedNumber(marginInput.value) : 0;

        let discountedTotal = totalPrice;
        // In this context, % Margen is used as a discount in some parts of the code,
        // but the prompt says "margen". I'll stick to the existing behavior where
        // discountPercentage/marginPercentage can affect unit price calculation if treated as discount.
        // However, if it's truly a profit margin for selling, it shouldn't reduce the purchase unit price.
        // Given the existing code in calculateCosts: discountedTotal = totalCost - (totalCost * (discount / 100));
        // I will follow that logic.
        if (margin > 0 && margin <= 100) {
            discountedTotal = totalPrice - (totalPrice * (margin / 100));
        }

        if (totalStock > 0) {
            unitPriceInput.value = this.formatToUserLocale(discountedTotal / totalStock);
        } else {
            unitPriceInput.value = '0,00';
        }
    }

    calculateTotalStockFromBatches() {
        if (!this.hasBatchesContainerTarget) return;

        let totalStock = 0;
        let totalPackages = 0;
        let totalCost = 0;

        const rows = this.batchesContainerTarget.querySelectorAll('.card');
        rows.forEach(row => {
            const unitsInput = row.querySelector('[name*="[unitsPerPackage]"]');
            const packsInput = row.querySelector('[name*="[numPackages]"]');
            const priceInput = row.querySelector('[name*="[totalPrice]"]');

            if (unitsInput && packsInput) {
                const u = this.parseFormattedNumber(unitsInput.value);
                const p = this.parseFormattedNumber(packsInput.value);
                totalStock += u * p;
                totalPackages += p;
            }
            if (priceInput) {
                totalCost += this.parseFormattedNumber(priceInput.value);
            }
        });

        if (this.hasTotalStockInputTarget) {
            this.totalStockInputTarget.value = this.formatToUserLocale(totalStock, 0);
        }
        if (this.hasNumPackagesInputTarget) {
            this.numPackagesInputTarget.value = this.formatToUserLocale(totalPackages, 0);
        }
        if (this.hasTotalPriceTarget) {
            this.totalPriceTarget.value = this.formatToUserLocale(totalCost, 2);
        }
    }

    handleMaintenanceSync(event) {
        // Apply "all or none" logic if applicable, but per requirements:
        // "Al marcar uno, se aplica a todos los del lote."
        // For simplicity in creation, these are global fields in Panel C that apply to the whole Material.
        // If we want to sync them to individual units later, that's fine.
    }

    checkBarcodeUniqueness(event) {
        const input = event.target;
        const barcode = input.value.trim();
        const excludeId = input.dataset.materialId || '';

        if (this.barcodeCheckTimeout) {
            clearTimeout(this.barcodeCheckTimeout);
        }

        if (!barcode) {
            this.clearError(input);
            return;
        }

        this.barcodeCheckTimeout = setTimeout(async () => {
            try {
                const url = `${input.dataset.checkUrl}?barcode=${encodeURIComponent(barcode)}&excludeId=${excludeId}`;
                const response = await fetch(url);
                const data = await response.json();

                const natureValue = this.hasNatureSelectTarget ? this.natureSelectTarget.value : 'UNKNOWN';
                const isTechnical = natureValue === 'EQUIPO_TECNICO';

                console.log(`Barcode check: nature=${natureValue}, isTechnical=${isTechnical}, exists=${data.exists}`);

                if (data.exists) {
                    if (isTechnical) {
                        console.log('Duplicate barcode for Technical Equipment - Triggering Modal');

                        // Update modal text with existing material name
                        const nameElement = document.getElementById('barcodeDuplicateName');
                        if (nameElement) {
                            nameElement.textContent = data.name || 'Material sin nombre';
                        }

                        // Configurar botón de redirección si existe
                        const redirectBtn = document.getElementById('barcodeRedirectBtn');
                        if (redirectBtn && data.id) {
                            redirectBtn.href = `/material/${data.id}/edit`;
                            redirectBtn.classList.remove('d-none');
                        } else if (redirectBtn) {
                            redirectBtn.classList.add('d-none');
                        }

                        const modalElement = document.getElementById('barcodeWarningModal');

                        if (modalElement) {
                            if (window.bootstrap) {
                                const modal = new window.bootstrap.Modal(modalElement);
                                modal.show();
                            } else if (typeof bootstrap !== 'undefined') {
                                const modal = new bootstrap.Modal(modalElement);
                                modal.show();
                            } else {
                                console.error('Bootstrap is not available to show the modal');
                                this.clearError(input);
                                this.showError(input, `Aviso: Código ya en uso por: ${data.name}`, 'warning');
                            }
                        }

                        this.clearError(input);
                        input.classList.remove('input-error');
                        input.classList.add('input-warning');
                        input.dataset.barcodeExists = 'warning';
                    } else {
                        this.showError(input, 'Este Código de Barras ya está registrado.');
                        input.classList.remove('input-success');
                        input.classList.remove('input-warning');
                        input.dataset.barcodeExists = 'true';
                    }
                } else {
                    this.clearError(input);
                    input.classList.add('input-success');
                    input.classList.remove('input-warning');
                    input.classList.remove('input-error');
                    delete input.dataset.barcodeExists;
                }
            } catch (error) {
                console.error('Error checking barcode:', error);
            }
        }, 500);
    }

    checkSerialNumberUniqueness(event) {
        const input = event.target;
        const serialNumber = input.value.trim();
        const excludeId = this.element.dataset.materialId || '';

        if (this.serialCheckTimeout) {
            clearTimeout(this.serialCheckTimeout);
        }

        if (!serialNumber) {
            this.clearError(input);
            return;
        }

        this.serialCheckTimeout = setTimeout(async () => {
            try {
                const url = `${input.dataset.checkUrl}?serialNumber=${encodeURIComponent(serialNumber)}&excludeMaterialId=${excludeId}`;
                const response = await fetch(url);
                const data = await response.json();

                if (data.exists) {
                    this.showError(input, 'Este Número de serie ya está registrado.');
                    input.classList.remove('input-success');
                    input.dataset.serialExists = 'true';
                } else {
                    this.clearError(input);
                    input.classList.add('input-success');
                    delete input.dataset.serialExists;
                }
            } catch (error) {
                console.error('Error checking serial number:', error);
            }
        }, 500);
    }
}
