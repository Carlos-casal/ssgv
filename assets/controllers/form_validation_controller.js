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

    // Sanitizes DNI/NIE input in real-time
    toUpperCase(event) {
        const input = event.target;
        // Allow numbers, letters X, Y, Z, and Ñ
        input.value = input.value.toUpperCase().replace(/[^A-Z0-9ÑXYZ]/g, '');
    }

    // Sanitizes Name input in real-time
    sanitizeAlpha(event) {
        const input = event.target;
        // Allow letters (including Spanish accents) and spaces
        input.value = input.value.replace(/[^a-zA-Z\u00C0-\u017F\s]/g, '');
    }

    // Sanitizes Last Name input in real-time
    sanitizeAlphaHyphen(event) {
        const input = event.target;
         // Allow letters (including Spanish accents), spaces, and hyphens
        input.value = input.value.replace(/[^a-zA-Z\u00C0-\u017F\s-]/g, '');
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
        // Find the actual input/select/textarea if the action was on a wrapper div
        const field = input.matches('input, select, textarea') ? input : input.querySelector('input, select, textarea');
        if (!field) return true; // Nothing to validate

        const [isValid, message] = this._getValidationRules(field);
        this._updateFieldValidationUI(field, isValid, message);
        return isValid;
    }

    _getValidationRules(input) {
        const value = input.value.trim();

        if (input.required && value === '') {
            return [false, 'Este campo es obligatorio.'];
        }

        if (value === '') return [true, ''];

        const inputId = input.id.toLowerCase();

        if (inputId.includes('dni')) {
            if (!this._validateDniNie(value)) return [false, 'El formato del DNI/NIE es incorrecto.'];
        }

        if (inputId.includes('name') && !inputId.includes('lastname')) {
             if (!/^[a-zA-Z\u00C0-\u017F\s]+$/.test(value)) {
                return [false, 'El nombre solo puede contener letras y espacios.'];
            }
        }

        if (inputId.includes('lastname')) {
             if (!/^[a-zA-Z\u00C0-\u017F\s-]+$/.test(value)) {
                return [false, 'Los apellidos solo pueden contener letras, espacios y guiones.'];
            }
        }

        if (inputId.includes('phone') || inputId.includes('contactphone')) {
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

        if (isValid) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');

            const errorContainer = document.createElement('div');
            errorContainer.className = 'form-error-message';
            errorContainer.textContent = message;
            // Insert after the input's direct parent, which is the floating label wrapper
            input.parentElement.after(errorContainer);
        }
    }

    _removeValidationUI(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const parent = input.parentElement;
        if (!parent) return;

        const nextElement = parent.nextElementSibling;
        if (nextElement && nextElement.classList.contains('form-error-message')) {
            nextElement.remove();
        }
    }
}