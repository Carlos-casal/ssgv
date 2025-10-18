import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.setAttribute('novalidate', 'novalidate'); // Disable native browser validation popups
        this.element.addEventListener('submit', this.handleSubmit.bind(this));

        // Add blur listeners to all relevant inputs for real-time validation
        this.element.querySelectorAll('input, select, textarea').forEach(input => {
            input.addEventListener('blur', (event) => this.validate(event.target));
        });

        // Keep conditional logic for specific fields
        this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]').forEach(checkbox => {
            checkbox.addEventListener('change', this.toggleDrivingLicenseExpiry.bind(this));
        });
        this.element.querySelectorAll('input[name="volunteer[hasVolunteeredBefore]"]').forEach(radio => {
            radio.addEventListener('change', this.togglePreviousInstitutions.bind(this));
        });

        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
    }

    // --- Conditional Field Logic (Kept from original) ---
    toggleDrivingLicenseExpiry() {
        const drivingLicensesCheckboxes = this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]');
        const expiryWrapper = document.getElementById('driving-license-expiry-wrapper');
        if (!expiryWrapper) return;

        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);
        expiryWrapper.classList.toggle('hidden', !anyChecked);
        const expiryInput = expiryWrapper.querySelector('input');
        if (expiryInput) {
            expiryInput.required = anyChecked;
            if (!anyChecked) this.removeValidation(expiryInput);
        }
    }

    togglePreviousInstitutions() {
        const hasVolunteeredYes = this.element.querySelector('input[name="volunteer[hasVolunteeredBefore]"][value="1"]');
        const institutionsWrapper = document.getElementById('previous-institutions-wrapper');
        if (!institutionsWrapper) return;

        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;
        institutionsWrapper.classList.toggle('hidden', !isYesChecked);
        const textarea = institutionsWrapper.querySelector('textarea');
        if (textarea) {
            textarea.required = isYesChecked;
            if (!isYesChecked) this.removeValidation(textarea);
        }
    }

    // --- Form Actions ---
    handleSubmit(event) {
        let isFormValid = true;
        // Validate all fields on submit
        this.element.querySelectorAll('input, select, textarea').forEach(input => {
            if (!this.validate(input)) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            const firstInvalid = this.element.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    }

    // --- Real-time Validation (on blur) ---
    validate(input) {
        // Don't validate hidden fields
        if (input.offsetParent === null) {
            return true;
        }

        const isValid = input.checkValidity();
        const message = this.getErrorMessage(input);
        this.updateFieldValidation(input, isValid, message);
        return isValid;
    }

    getErrorMessage(input) {
        const validity = input.validity;

        if (validity.valueMissing) {
            return 'Este campo es obligatorio.';
        }
        if (validity.typeMismatch) {
            if (input.type === 'email') return 'Por favor, introduce una dirección de correo electrónico válida.';
            if (input.type === 'url') return 'Por favor, introduce una URL válida.';
            return 'El formato no es el correcto.';
        }
        if (validity.tooShort) {
            return `El valor es demasiado corto. Mínimo ${input.minLength} caracteres.`;
        }
        if (validity.tooLong) {
            return `El valor es demasiado largo. Máximo ${input.maxLength} caracteres.`;
        }
        if (validity.patternMismatch) {
            return 'El formato no es válido.';
        }
        if (validity.rangeUnderflow) {
            return `El valor debe ser superior a ${input.min}.`;
        }
        if (validity.rangeOverflow) {
            return `El valor debe ser inferior a ${input.max}.`;
        }
        if (validity.stepMismatch) {
            return 'El valor no es válido.';
        }
        if (validity.badInput) {
            return 'Por favor, introduce un número válido.';
        }
        return ''; // No error
    }

    // --- UI Update Helpers ---
    updateFieldValidation(input, isValid, message) {
        this.removeValidation(input);
        const parent = input.parentElement; // Target the parent for floating label styles

        if (isValid) {
            input.classList.add('is-valid');
            input.classList.remove('is-invalid');
        } else {
            input.classList.add('is-invalid');
            input.classList.remove('is-valid');

            const errorEl = document.createElement('p');
            errorEl.className = 'form-error-message'; // Using class from app.css
            errorEl.textContent = message;
            // Insert after the input, not inside the relative wrapper
            parent.insertAdjacentElement('afterend', errorEl);
        }
    }

    removeValidation(input) {
        const parent = input.parentElement;
        input.classList.remove('is-valid', 'is-invalid');

        // Find and remove the specific error message for this input
        const nextSibling = parent.nextElementSibling;
        if (nextSibling && nextSibling.classList.contains('form-error-message')) {
            nextSibling.remove();
        }
    }
}