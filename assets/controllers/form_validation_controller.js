import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    // On blur, validate the specific field that triggered the event.
    validateField(event) {
        const field = event.currentTarget.querySelector('input, select, textarea');
        if (!field) return;

        const validationType = field.dataset.validate;
        if (!validationType) return; // No specific validation for this field

        const { isValid, message } = this._getValidationResult(field);
        this._updateFieldUI(field, isValid, message);
    }

    // On submit, validate all required fields and prevent submission if any are invalid.
    handleSubmit(event) {
        let isFormValid = true;

        this.element.querySelectorAll('[required]').forEach(field => {
            const { isValid, message } = this._getValidationResult(field);
            this._updateFieldUI(field, isValid, message);
            if (!isValid) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
            const firstInvalid = this.element.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.focus();
            }
        }
    }

    // Central validation logic dispatcher
    _getValidationResult(field) {
        const value = field.value.trim();
        const validationType = field.dataset.validate;

        // Check for required first
        if (field.required && value === '') {
            return { isValid: false, message: 'Este campo es obligatorio.' };
        }

        // If not required and empty, it's valid.
        if (!field.required && value === '') {
             return { isValid: true, message: '' };
        }

        // Dispatch to specific format validators
        switch (validationType) {
            case 'dni':
                return this._validateDNI(value);
            case 'email':
                return this._validateEmail(value);
            case 'phone':
                 return this._validatePhone(value);
            case 'name':
            case 'lastName':
                return this._validateName(value);
            default:
                return { isValid: true, message: '' };
        }
    }

    // --- Specific Validators ---

    _validateDNI(value) {
        const nifRegex = /^((\d{8})|([XYZ]\d{7}))[A-Z]$/;
        if (!nifRegex.test(value.toUpperCase())) {
            return { isValid: false, message: 'Formato de DNI/NIE incorrecto.' };
        }
        const numberPart = value.substr(0, value.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = value.substr(value.length - 1).toUpperCase();
        const validLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const calculatedLetter = validLetters.charAt(parseInt(numberPart, 10) % 23);

        if (letter !== calculatedLetter) {
            return { isValid: false, message: 'La letra del DNI/NIE no es correcta.' };
        }
        return { isValid: true, message: '' };
    }

    _validateEmail(value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            return { isValid: false, message: 'Formato de email incorrecto.' };
        }
        return { isValid: true, message: '' };
    }

    _validatePhone(value) {
        const phoneRegex = /^\+?[0-9\s]{9,15}$/;
        if (!phoneRegex.test(value)) {
            return { isValid: false, message: 'El teléfono debe tener entre 9 y 15 dígitos.' };
        }
        return { isValid: true, message: '' };
    }

    _validateName(value) {
        const nameRegex = /^[a-zA-Z\u00C0-\u017F\s-]+$/;
        if (!nameRegex.test(value)) {
            return { isValid: false, message: 'Solo se admiten letras, espacios y guiones.' };
        }
        return { isValid: true, message: '' };
    }

    // --- UI Update ---

    _updateFieldUI(field, isValid, message) {
        const errorContainer = field.parentElement.querySelector('.form-error-message');

        field.classList.remove('is-valid', 'is-invalid');
        if (errorContainer) errorContainer.textContent = '';

        if (isValid) {
            if (field.value.trim() !== '') {
                field.classList.add('is-valid');
            }
        } else {
            field.classList.add('is-invalid');
            if (errorContainer) {
                errorContainer.textContent = message;
            }
        }
    }

    // --- Real-time Sanitizers ---

    sanitizeDNI(event) {
        const input = event.currentTarget;
        input.value = input.value.toUpperCase().replace(/[^A-Z0-9ÑXYZ]/g, '');
    }

    sanitizeAlpha(event) {
        const input = event.currentTarget;
        input.value = input.value.replace(/[^a-zA-Z\u00C0-\u017F\s-]/g, '');
    }
}