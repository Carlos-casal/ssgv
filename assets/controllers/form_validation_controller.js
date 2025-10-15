/**
 * @file Stimulus controller for real-time form validation and dynamic field interactions.
 */
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

    /**
     * Converts the DNI input to uppercase as the user types.
     */
    toUpperCase(event) {
        event.target.value = event.target.value.toUpperCase();
    }

    /**
     * Toggles the visibility of the driving license expiry date field based on checkbox selections.
     */
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

    /**
     * Toggles the visibility of the "previous institutions" textarea based on radio button selection.
     */
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

    /**
     * Handles the form submission, preventing it if validation fails.
     */
    handleSubmit(event) {
        let isFormValid = true;
        this.element.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.required || (input.type !== 'file' && input.value.trim() !== '')) {
                if (!this._validateInput(input)) {
                    isFormValid = false;
                }
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

    /**
     * Validates a single field on the 'blur' event.
     */
    validateField(event) {
        this._validateInput(event.target);
    }

    /**
     * Core validation function for a given input.
     * @param {HTMLElement} input
     * @returns {boolean}
     */
    _validateInput(input) {
        const [isValid, message] = this._getValidationRules(input);
        this._updateFieldValidationUI(input, isValid, message);
        return isValid;
    }

    /**
     * Gets validation rules and messages for an input.
     * @param {HTMLElement} input
     * @returns {[boolean, string]}
     */
    _getValidationRules(input) {
        const value = input.value.trim();

        if (input.required && value === '') {
            return [false, 'Este campo es obligatorio.'];
        }

        if (!input.required && value === '') {
            return [true, ''];
        }

        if (input.id.includes('dni') && !this._validateDniNie(value)) {
            return [false, 'El DNI/NIE no es válido.'];
        }

        if ((input.id.includes('phone') || input.id.includes('contactPhone')) && !/^[679]\d{8}$/.test(value.replace(/\s+/g, ''))) {
            return [false, 'El formato del teléfono no es válido (9 dígitos sin prefijo).'];
        }

        if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
            return [false, 'El formato del correo no es válido.'];
        }

        return [true, ''];
    }

    /**
     * Validates a Spanish DNI or NIE.
     * @param {string} value
     * @returns {boolean}
     */
    _validateDniNie(value) {
        const dni = value.toUpperCase().trim();
        if (!/^((\d{8})|([XYZ]\d{7}))[A-Z]$/.test(dni)) return false;
        const numberPart = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);
        const controlLetter = 'TRWAGMYFPDXBNJZSQVHLCKE'[parseInt(numberPart, 10) % 23];
        return letter === controlLetter;
    }

    /**
     * Updates the UI to show validation status.
     * @param {HTMLElement} input
     * @param {boolean} isValid
     * @param {string} [message='']
     */
    _updateFieldValidationUI(input, isValid, message = '') {
        this._removeValidationUI(input);
        const fieldContainer = input.closest('.form-group') || input.parentElement;
        if (isValid) {
            input.classList.add('is-valid');
        } else {
            input.classList.add('is-invalid');
            const errorContainer = document.createElement('div');
            errorContainer.className = 'invalid-feedback';
            errorContainer.textContent = message;
            fieldContainer.appendChild(errorContainer);
        }
    }

    /**
     * Removes all validation indicators from a field.
     * @param {HTMLElement} input
     */
    _removeValidationUI(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const fieldContainer = input.closest('.form-group') || input.parentElement;
        const error = fieldContainer.querySelector('.invalid-feedback');
        if (error) {
            error.remove();
        }
    }
}