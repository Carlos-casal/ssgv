/**
 * @file Stimulus controller for real-time form validation and dynamic field interactions.
 *
 * This controller handles:
 * - Real-time validation of individual fields on blur.
 * - Special validation for DNI/NIE, phone numbers, and email formats.
 * - A final validation pass on form submission.
 * - Toggling the visibility of conditional fields (e.g., driving license expiry date).
 * - Displaying a modal for age verification for volunteers between 16 and 18 years old.
 * - Resetting the form for a new entry without a page reload.
 *
 * It is designed to be robust by using Stimulus targets and data attributes to decouple
 * the controller from the specific HTML structure or CSS classes.
 */
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = [
        "dateOfBirthInput",
        "ageModal",
    ];

    connect() {
        // Set initial state for conditional fields when the form loads.
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
        // Set the max date for the date of birth input to prevent selecting future dates for under-16s.
        this.setDateOfBirthMaxDate();
    }

    /**
     * Handles the form submission.
     * Prevents submission and highlights errors if the form is invalid.
     */
    handleSubmit(event) {
        let isFormValid = true;
        // Validate all fields that are required or have a value.
        this.element.querySelectorAll('input, select, textarea').forEach(input => {
            if (input.required || input.value.trim() !== '') {
                if (!this._validateInput(input)) {
                    isFormValid = false;
                }
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            // Scroll to the first invalid field to bring it to the user's attention.
            const firstInvalid = this.element.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    /**
     * Validates a single field, typically on the 'blur' event.
     */
    validateField(event) {
        this._validateInput(event.target);
    }

    /**
     * Provides real-time validation for the DNI/NIE field as the user types.
     * Only shows a success state to avoid showing errors prematurely.
     */
    validateDniOnInput(event) {
        const input = event.target;
        const isValid = this._validateDniNie(input.value);
        if (isValid) {
            // Show green checkmark if valid.
            this._updateFieldValidationUI(input, true);
        } else {
            // Clear validation if it becomes invalid while typing.
            this._removeValidationUI(input);
        }
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
            // Clear validation state if the field is hidden.
            if (!anyChecked) this._removeValidationUI(expiryInput);
        }
    }

    /**
     * Toggles the visibility of the "previous institutions" field.
     * This field is only required and shown if the user indicates they have prior volunteer experience.
     */
    togglePreviousInstitutions() {
        const hasVolunteeredYes = this.element.querySelector('input[name="volunteer[hasVolunteeredBefore]"][value="1"]');
        const institutionsWrapper = document.getElementById('previous-institutions-wrapper');
        if (!institutionsWrapper) return;

        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;
        institutionsWrapper.classList.toggle('hidden', !isYesChecked);
        const textarea = institutionsWrapper.querySelector('textarea');
        if (textarea) {
            textarea.required = isYesChecked;
            // Clear validation state if the field is hidden.
            if (!isYesChecked) this._removeValidationUI(textarea);
        }
    }

    // --- Age Modal ---

    /**
     * Displays the age verification modal.
     */
    _showAgeModal() {
        if (this.hasAgeModalTarget) {
            this.ageModalTarget.classList.remove('hidden');
        }
    }

    /**
     * Handles the user's acceptance of the parental authorization.
     * Hides the modal and makes the date input read-only to prevent changes.
     */
    acceptAuthorization() {
        if (this.hasAgeModalTarget) {
            this.ageModalTarget.classList.add('hidden');
        }
        if (this.hasDateOfBirthInputTarget) {
            this.dateOfBirthInputTarget.readOnly = true;
            // Re-validate to ensure the green check appears and stays
            this._validateInput(this.dateOfBirthInputTarget);
        }
    }

    /**
     * Handles the user's cancellation of the authorization.
     * Redirects the user away from the registration form.
     */
    cancelAuthorization() {
        window.location.href = '/'; // Redirect to a safe page (e.g., homepage).
    }

    /**
     * Parses a YYYY-MM-DD string into a Date object in a timezone-safe way.
     * This avoids issues where `new Date('YYYY-MM-DD')` can be interpreted as UTC midnight
     * and shift the date by a day depending on the user's timezone.
     * @param {string} dateString The date string in YYYY-MM-DD format.
     * @returns {Date|null}
     */
    _parseDate(dateString) {
        if (!dateString) return null;
        const parts = dateString.split('-');
        if (parts.length !== 3) return null;
        // new Date(year, monthIndex, day) correctly handles it as local time.
        return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
    }

    // --- Core Validation Logic ---

    /**
     * Central validation function that orchestrates validation for a given input.
     * @param {HTMLElement} input The input element to validate.
     * @returns {boolean} True if the input is valid, false otherwise.
     */
    _validateInput(input) {
        // Don't re-validate the date of birth if it has been locked after modal acceptance.
        if (input.id === 'volunteer_dateOfBirth' && input.readOnly) {
            this._updateFieldValidationUI(input, true); // Ensure it stays green
            return true;
        }

        const [isValid, message] = this._getValidationRules(input);

        // Special handling for date of birth to manage the 16-18 age gate.
        if (input.id === 'volunteer_dateOfBirth') {
            if (isValid) {
                const birthDate = this._parseDate(input.value); // Use timezone-safe parser
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const eighteenYearsAgo = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());

                // The modal should only show if the date is valid AND the person is under 18.
                if (birthDate > eighteenYearsAgo) {
                    this._showAgeModal();
                }
                // Mark as valid regardless of whether the modal is shown.
                this._updateFieldValidationUI(input, true, '');
            } else {
                // If invalid (e.g., under 16), show the error.
                this._updateFieldValidationUI(input, false, message);
            }
            return isValid;
        }

        // Standard validation for all other fields.
        this._updateFieldValidationUI(input, isValid, message);
        return isValid;
    }

    /**
     * Gets the validation result for a given input based on its type and constraints.
     * @param {HTMLElement} input The input element.
     * @returns {[boolean, string]} A tuple containing the validity state and an error message.
     */
    _getValidationRules(input) {
        const value = input.value.trim();

        // 1. Check for required fields
        if (input.required && value === '') {
            return [false, 'Este campo es obligatorio.'];
        }

        // 2. Skip validation for non-required empty fields
        if (!input.required && value === '') {
            return [true, ''];
        }

        // 3. Apply specific validation rules based on field ID
        switch (input.id) {
            case 'volunteer_dni':
                if (!this._validateDniNie(value)) {
                    return [false, 'El DNI/NIE no es válido.'];
                }
                break;
            case 'volunteer_phone':
            case 'volunteer_contactPhone1':
                if (!/^[679]\d{8}$/.test(value.replace(/\s+/g, ''))) {
                    return [false, 'El formato del teléfono no es válido (9 dígitos sin prefijo).'];
                }
                break;
            case 'volunteer_email':
                if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                    return [false, 'El formato del correo no es válido.'];
                }
                break;
            case 'volunteer_dateOfBirth':
                const birthDate = this._parseDate(input.value); // Use timezone-safe parser
                if (!birthDate || isNaN(birthDate.getTime())) {
                     return [false, 'La fecha no es válida.'];
                }
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                const sixteenYearsAgo = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());

                if (birthDate > sixteenYearsAgo) {
                    return [false, 'El voluntario debe tener al menos 16 años.'];
                }
                break;
        }

        // If no specific rules failed, the field is valid.
        return [true, ''];
    }

    /**
     * Validates a Spanish DNI or NIE number.
     * @param {string} value The DNI/NIE to validate.
     * @returns {boolean} True if valid.
     */
    _validateDniNie(value) {
        const dni = value.toUpperCase().trim();
        if (!/^((\d{8})|([XYZ]\d{7}))[A-Z]$/.test(dni)) return false;

        const numberPart = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);
        const controlLetter = 'TRWAGMYFPDXBNJZSQVHLCKE'[parseInt(numberPart, 10) % 23];

        return letter === controlLetter;
    }

    // --- UI Update Helpers ---

    /**
     * Updates the UI to show validation status (success or error).
     * @param {HTMLElement} input The input element.
     * @param {boolean} isValid The validation status.
     * @param {string} [message=''] The error message to display if invalid.
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
     * Removes all validation indicators (styles, icons, messages) from a field.
     * @param {HTMLElement} input The input element.
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

    // --- Misc ---

    /**
     * Sets the 'max' attribute for the date of birth input to prevent selecting dates for anyone under 16.
     */
    setDateOfBirthMaxDate() {
        if (!this.hasDateOfBirthInputTarget) return;

        const today = new Date();
        const maxYear = today.getFullYear() - 16;
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        const maxDate = `${maxYear}-${month}-${day}`;
        this.dateOfBirthInputTarget.setAttribute('max', maxDate);
    }

    /**
     * Resets the form to its initial state to allow for another entry.
     */
    addAnother(event) {
        event.preventDefault();
        this.element.reset(); // Resets form field values.

        // Clear all validation UI from the form.
        this.element.querySelectorAll('.validation-icon').forEach(el => el.remove());
        this.element.querySelectorAll('[data-error-container]').forEach(el => {
            el.textContent = '';
            el.classList.add('hidden');
        });
        this.element.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
        });

        // Re-initialize conditional fields.
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();

        // Ensure date input is not readonly
        if (this.hasDateOfBirthInputTarget) {
            this.dateOfBirthInputTarget.readOnly = false;
        }

        // Scroll to the top of the page.
        window.scrollTo(0, 0);
    }
}