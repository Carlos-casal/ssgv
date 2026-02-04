import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['category', 'nature', 'sizingType', 'title', 'barcodeRow', 'descriptionRow', 'sizeField', 'subtypeField'];

    connect() {
        this.updateFieldVisibility();
        this.updateFormTitle();
    }

    updateFieldVisibility() {
        const category = this.hasCategoryTarget ? this.categoryTarget.value : '';
        const nature = this.hasNatureTarget ? this.natureTarget.value : '';

        // Show/hide fields based on category
        if (category === 'Uniformidad') {
            this.showUniformityFields();
        } else if (nature === 'EQUIPO_TECNICO') {
            this.showTechnicalFields();
        } else {
            this.showGenericFields();
        }

        this.updateFormTitle();
    }

    updateFormTitle() {
        if (!this.hasTitleTarget) return;

        const category = this.hasCategoryTarget ? this.categoryTarget.value : '';

        const titles = {
            'Sanitario': 'Registro de Material Sanitario',
            'Comunicaciones': 'Registro de Comunicaciones',
            'Logística': 'Registro de Material Logístico',
            'Mar': 'Registro de Material Marítimo',
            'Uniformidad': 'Registro de Uniformidad y Vestuario',
            'Varios': 'Registro de Material Varios'
        };

        this.titleTarget.textContent = titles[category] || 'Registrar Nuevo Material';
    }

    showUniformityFields() {
        // Show barcode and description
        if (this.hasBarcodeRowTarget) this.barcodeRowTarget.style.display = 'block';
        if (this.hasDescriptionRowTarget) this.descriptionRowTarget.style.display = 'block';

        // Show subtype field for uniformity
        if (this.hasSubtypeFieldTarget) this.hasSubtypeFieldTarget.style.display = 'block';
    }

    showTechnicalFields() {
        // Show barcode prominently
        if (this.hasBarcodeRowTarget) this.barcodeRowTarget.style.display = 'block';
        if (this.hasDescriptionRowTarget) this.descriptionRowTarget.style.display = 'block';
    }

    showGenericFields() {
        // Show all fields
        if (this.hasBarcodeRowTarget) this.barcodeRowTarget.style.display = 'block';
        if (this.hasDescriptionRowTarget) this.descriptionRowTarget.style.display = 'block';
    }

    updateSizeField(event) {
        if (!this.hasSizeFieldTarget) return;

        const subtype = event.target.value;
        const sizeField = this.sizeFieldTarget;

        // Clear current field
        sizeField.innerHTML = '';

        if (subtype === 'ropa_superior') {
            // Polo, Chaqueta: XS, S, M, L, XL, XXL, 3XL
            this.createSelectField(sizeField, ['XS', 'S', 'M', 'L', 'XL', 'XXL', '3XL']);
        } else if (subtype === 'pantalon') {
            // Pantalón: 34, 36, 38, 40, 42, 44, 46, 48, 50
            const sizes = [];
            for (let i = 34; i <= 50; i += 2) {
                sizes.push(i.toString());
            }
            this.createSelectField(sizeField, sizes);
        } else if (subtype === 'calzado') {
            // Calzado: Input numérico 35-48
            this.createNumberInput(sizeField, 35, 48);
        } else if (subtype === 'complemento') {
            // Gorra, etc: Talla única
            this.createUniqueSize(sizeField);
        }
    }

    createSelectField(container, options) {
        const select = document.createElement('select');
        select.className = 'form-control';
        select.name = 'size';
        select.required = true;

        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = 'Seleccionar talla...';
        select.appendChild(defaultOption);

        options.forEach(opt => {
            const option = document.createElement('option');
            option.value = opt;
            option.textContent = opt;
            select.appendChild(option);
        });

        container.appendChild(select);
    }

    createNumberInput(container, min, max) {
        const input = document.createElement('input');
        input.type = 'number';
        input.className = 'form-control';
        input.name = 'size';
        input.min = min;
        input.max = max;
        input.placeholder = `Talla (${min}-${max})`;
        input.required = true;

        container.appendChild(input);
    }

    createUniqueSize(container) {
        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'form-control';
        input.name = 'size';
        input.value = 'Única';
        input.readOnly = true;

        container.appendChild(input);
    }
}
