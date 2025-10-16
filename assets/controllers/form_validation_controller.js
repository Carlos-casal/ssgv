import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "drivingLicenseContainer", "drivingLicenseDate",
        "previousVolunteeringInstitutions"
    ];

    connect() {
        this.toggleDrivingLicenseDate();
        this.togglePreviousVolunteering();
    }

    toUpperCase(event) {
        const input = event.target;
        const originalValue = input.value;
        const sanitizedValue = originalValue.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (originalValue !== sanitizedValue) {
            input.value = sanitizedValue;
        }
    }

    toggleDrivingLicenseDate() {
        if (!this.hasDrivingLicenseContainerTarget || !this.hasDrivingLicenseDateTarget) return;
        const drivingLicensesCheckboxes = this.drivingLicenseContainerTarget.querySelectorAll('input[type="checkbox"]');
        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);
        this.drivingLicenseDateTarget.classList.toggle('hidden', !anyChecked);
        const expiryInput = this.drivingLicenseDateTarget.querySelector('input');
        if (expiryInput) {
            expiryInput.required = anyChecked;
            if (!anyChecked) this._removeValidationUI(expiryInput);
        }
    }

    togglePreviousVolunteering() {
        if (!this.hasPreviousVolunteeringInstitutionsTarget) return;
        const hasVolunteeredYes = this.element.querySelector('input[name*="[hasVolunteeredBefore]"][value="1"]');
        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;
        this.previousVolunteeringInstitutionsTarget.classList.toggle('hidden', !isYesChecked);
        const textarea = this.previousVolunteeringInstitutionsTarget.querySelector('textarea');
        if (textarea) {
            textarea.required = isYesChecked;
            if (!isYesChecked) this._removeValidationUI(textarea);
        }
    }

    handleSubmit(event) {
        let isFormValid = true;
        this.element.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            if (!this._validateInput(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            const firstInvalid = this.element.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    validateField(event) {
        this._validateInput(event.target);
    }

    _validateInput(input) {
        const [isValid, message] = this._getValidationRules(input);
        this._updateFieldValidationUI(input, isValid, message);
        return isValid;
    }

    _getValidationRules(input) {
        const value = input.value.trim();

        if (input.required && value === '') {
            return [false, 'Este campo es obligatorio.'];
        }

        if (value === '') return [true, ''];

        if (input.id.includes('dni')) {
            if(!this._validateDniNie(value)) return [false, 'El DNI/NIE no es válido.'];
        }

        if (input.id.includes('phone') || input.id.includes('contactPhone')) {
            const phoneValue = value.replace(/[\s+]/g, '');
            if (!/^\d{9,15}$/.test(phoneValue)) {
                 return [false, 'El teléfono debe tener entre 9 y 15 dígitos.'];
            }
        }

        if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return [false, 'El formato del correo no es válido.'];
        }

        return [true, ''];
    }

    _validateDniNie(value) {
        const dni = value.toUpperCase().trim();
        if (!/^((\d{8})|([XYZ]\d{7}))[A-Z]$/.test(dni)) return false;
        const numberPart = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);
        const controlLetter = 'TRWAGMYFPDXBNJZSQVHLCKE'[parseInt(numberPart, 10) % 23];
        return letter === controlLetter;
    }

    _updateFieldValidationUI(input, isValid, message = '') {
        this._removeValidationUI(input);
        const parent = input.closest('div');
        if (!parent) return;

        parent.style.position = 'relative';

        const icon = document.createElement('span');
        icon.className = 'validation-icon';

        if (isValid) {
            input.classList.add('is-valid');
            icon.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        } else {
            input.classList.add('is-invalid');
            icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;

            const errorContainer = document.createElement('div');
            errorContainer.className = 'invalid-feedback';
            errorContainer.textContent = message;
            parent.appendChild(errorContainer);
        }
        parent.appendChild(icon);
    }

    _removeValidationUI(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const parent = input.closest('div');
        if (!parent) return;
        const icon = parent.querySelector('.validation-icon');
        if (icon) icon.remove();
        const error = parent.querySelector('.invalid-feedback');
        if (error) error.remove();
    }
}