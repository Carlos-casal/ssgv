import { Controller } from '@hotwired/stimulus';

/**
 * Controller to handle real-time form validation and interactivity.
 */
export default class extends Controller {
    connect() {
        // Run initial checks for conditional fields on page load.
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
    }

    /**
     * Shows or hides the driving license expiry date field based on whether
     * any driving license checkbox is selected.
     */
    toggleDrivingLicenseExpiry() {
        const drivingLicensesCheckboxes = this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]');
        const expiryWrapper = document.getElementById('driving-license-expiry-wrapper');
        const expiryInput = document.getElementById('volunteer_drivingLicenseExpiryDate');

        if (!expiryWrapper || !expiryInput) return;

        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);

        expiryWrapper.classList.toggle('hidden', !anyChecked);
        expiryInput.required = anyChecked;
        if (!anyChecked) {
            this.clearValidation(expiryInput);
        }
    }

    /**
     * Shows or hides the previous volunteering institutions field based on
     * the answer to "have you volunteered before?".
     */
    togglePreviousInstitutions() {
        const hasVolunteeredYes = this.element.querySelector('input[name="volunteer[hasVolunteeredBefore]"][value="1"]');
        const institutionsWrapper = document.getElementById('previous-institutions-wrapper');
        const institutionsInput = document.getElementById('volunteer_previousVolunteeringInstitutions');

        if (!institutionsWrapper || !institutionsInput) return;

        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;

        institutionsWrapper.classList.toggle('hidden', !isYesChecked);
        institutionsInput.required = isYesChecked;
        if (!isYesChecked) {
            this.clearValidation(institutionsInput);
        }
    }

    /**
     * Displays a preview of the selected profile picture.
     */
    previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('preview-element');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                preview.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /**
     * Main validation function triggered by form elements on input/change.
     */
    validate(event) {
        const input = event.target;
        let isValid = true;
        let message = '';

        // --- Required Check ---
        if (input.required && input.value.trim() === '') {
            isValid = false;
            message = 'Este campo es obligatorio.';
        }

        // --- Specific Field Validations (only if required check passed) ---
        if (isValid) {
            switch (input.id) {
                case 'volunteer_phone':
                    // E.164-like regex for international numbers
                    const phoneRegex = /^\+?[1-9]\d{1,14}$/;
                    if (!phoneRegex.test(input.value)) {
                        isValid = false;
                        message = 'El formato del teléfono no es válido.';
                    } else if (input.value.startsWith('+34')) {
                        // Strip +34 for Spanish numbers as requested
                        input.value = input.value.substring(3);
                    }
                    break;

                case 'volunteer_dateOfBirth':
                    const birthDate = new Date(input.value);
                    if (isNaN(birthDate.getTime())) break; // Don't validate if date is invalid
                    const today = new Date();
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    if (age < 16) {
                        isValid = false;
                        message = 'El voluntario debe tener al menos 16 años.';
                    }
                    break;
            }
        }

        // --- Apply styles and messages ---
        if (isValid) {
            this.setValid(input);
        } else {
            this.setInvalid(input, message);
        }
    }

    // --- Helper functions for applying styles and error messages ---

    setValid(input) {
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
        this.removeErrorMessage(input);
    }

    setInvalid(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        this.addErrorMessage(input, message);
    }

    clearValidation(input) {
        input.classList.remove('is-valid', 'is-invalid');
        this.removeErrorMessage(input);
    }

    addErrorMessage(input, message) {
        this.removeErrorMessage(input); // Remove old message first to prevent duplicates
        const parent = input.closest('div');
        const errorEl = document.createElement('p');
        errorEl.className = 'form-error-message';
        errorEl.textContent = message;
        parent.appendChild(errorEl);
    }

    removeErrorMessage(input) {
        const parent = input.closest('div');
        const errorEl = parent.querySelector('.form-error-message');
        if (errorEl) {
            errorEl.remove();
        }
    }
}