import { Controller } from '@hotwired/stimulus';

/**
 * Controller to handle form validation feedback and interactions.
 * It provides real-time validation, manages conditional UI elements like modals, and handles DNI formatting.
 */
export default class extends Controller {
    static targets = ["modal", "dateOfBirthInput"];

    connect() {
        this.setDefaultDateOfBirth();
        this.setDateOfBirthMaxDate();
    }

    /**
     * Sets the default value for the date of birth input to exactly 16 years ago from today.
     */
    setDefaultDateOfBirth() {
        const dateOfBirthInput = this.element.querySelector('[name="volunteer[dateOfBirth]"]');
        if (dateOfBirthInput && !dateOfBirthInput.value) {
            const today = new Date();
            const sixteenYearsAgo = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
            // Format to YYYY-MM-DD for the input
            const formattedDate = sixteenYearsAgo.toISOString().split('T')[0];
            dateOfBirthInput.value = formattedDate;
        }
    }

    /**
     * Converts the input value of an element to uppercase.
     * Designed for the DNI/NIE field.
     * @param {Event} event - The input event.
     */
    toUpperCase(event) {
        event.target.value = event.target.value.toUpperCase();
    }

    /**
     * Validates a single field on blur and provides real-time feedback for DNI on input.
     */
    validateField(event) {
        const input = event.target;
        const isDniOnInput = input.id === 'volunteer_dni' && event.type === 'input';
        this._validateInput(input, isDniOnInput);
    }

    /**
     * Validates the selected date of birth to check if the user is at least 16 years old.
     * If the user is younger than 16, it displays a warning modal.
     * This is a specific action for the date field's `change` event.
     * @param {Event} event - The change event from the date input.
     */
    validateAge(event) {
        const birthDate = new Date(event.target.value);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();

        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age < 18) {
            this.openModal();
        }
        // Also run standard validation to show checkmark
        this._validateInput(event.target);
    }

    /**
     * Sets the 'max' attribute for the date of birth input to prevent selecting dates for anyone under 16.
     */
    setDateOfBirthMaxDate() {
        if (!this.hasDateOfBirthInputTarget) return;

        const today = new Date();
        const maxYear = today.getFullYear() - 16;
        const minYear = today.getFullYear() - 100;
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        const maxDate = `${maxYear}-${month}-${day}`;
        const minDate = `${minYear}-${month}-${day}`;

        this.dateOfBirthInputTarget.setAttribute('max', maxDate);
        this.dateOfBirthInputTarget.setAttribute('min', minDate);
    }

    /**
     * Toggles the visibility of the driving license expiry date field.
     * This field is only required and shown if a driving license is selected.
     */
    toggleDrivingLicenseExpiry() {
        const drivingLicensesCheckboxes = this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]');
        const expiryWrapper = document.getElementById('driving-license-expiry-wrapper');
        if (!expiryWrapper) return;

        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);
        expiryWrapper.classList.toggle('hidden', !anyChecked);
        const expiryInput = expiryWrapper.querySelector('input');
        if (expiryInput) {
            expiryInput.required = anyChecked;
            if (!anyChecked) this._removeValidationUI(expiryInput);
        }
    }

    /**
     * Core validation logic.
     * @param {HTMLElement} input The input element to validate.
     * @param {boolean} isRealtime A flag to indicate if this is a real-time input check (for DNI).
     * @returns {boolean} True if valid.
     */
    _validateInput(input, isRealtime = false) {
        const [isValid, message] = this._getValidationRules(input);

        // For real-time DNI validation, only show the success state.
        if (isRealtime) {
            if (isValid) {
                this._updateFieldValidationUI(input, true);
            } else {
                this._removeValidationUI(input);
            }
        } else {
            this._updateFieldValidationUI(input, isValid, message);
        }

        return isValid;
    }

    /**
     * Gets validation rules for a given input.
     * @param {HTMLElement} input The input element.
     * @returns {[boolean, string]} A tuple [isValid, errorMessage].
     */
    _getValidationRules(input) {
        const value = input.value.trim();

        if (input.required && value === '') {
            return [false, 'Este campo es obligatorio.'];
        }

        if (!input.required && value === '') {
            return [true, ''];
        }

        switch (input.id) {
            case 'volunteer_dni':
                return [this._validateDniNie(value), 'El formato del DNI/NIE no es válido.'];
            case 'volunteer_phone':
            case 'volunteer_contactPhone1':
            case 'volunteer_contactPhone2':
                 if (!/^[679]\d{8}$/.test(value.replace(/\s+/g, ''))) {
                    return [false, 'El formato del teléfono no es válido (9 dígitos sin prefijo).'];
                }
                break;
            case 'user_email': // Note the ID change from the form
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    return [false, 'El formato del correo no es válido.'];
                }
                break;
        }

        return [true, ''];
    }

    /**
     * Validates a Spanish DNI or NIE.
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
     */
    _updateFieldValidationUI(input, isValid, message = '') {
        this._removeValidationUI(input); // Clear previous state first.

        const fieldContainer = input.closest('[data-field-container]');
        if (!fieldContainer) return;

        const iconContainer = input.parentElement; // Assumes input is wrapped for icon positioning.
        const icon = document.createElement('span');
        icon.className = 'validation-icon absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center';

        if (isValid) {
            input.classList.add('is-valid');
            icon.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        } else {
            input.classList.add('is-invalid');
            icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;

            const errorContainer = fieldContainer.querySelector('[data-error-container]');
            if (errorContainer && message) {
                errorContainer.textContent = message;
                errorContainer.classList.remove('hidden');
            }
        }
        iconContainer.appendChild(icon);
    }

    /**
     * Removes all validation indicators from a field.
     */
    _removeValidationUI(input) {
        input.classList.remove('is-valid', 'is-invalid');

        const fieldContainer = input.closest('[data-field-container]');
        if (!fieldContainer) return;

        // Remove validation icon
        const icon = input.parentElement.querySelector('.validation-icon');
        if (icon) {
            icon.remove();
        }

        // Hide and clear error message
        const errorContainer = fieldContainer.querySelector('[data-error-container]');
        if (errorContainer) {
            errorContainer.textContent = '';
            errorContainer.classList.add('hidden');
        }
    }

    /**
     * Opens the age warning modal.
     */
    openModal() {
        const modal = document.getElementById('age-warning-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    /**
     * Closes the age warning modal.
     */
    closeModal() {
        const modal = document.getElementById('age-warning-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}