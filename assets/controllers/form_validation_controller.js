import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.setAttribute('novalidate', 'true');
        this.element.addEventListener('submit', this.handleSubmit.bind(this));
    }

    validate(event) {
        const field = event.target;
        this.checkValidity(field);
    }

    checkValidity(field) {
        this.removeError(field);

        let isValid = field.checkValidity();
        let customErrorMessage = null;

        // Custom validation for DNI/NIE
        if (field.id === 'volunteer_dni' || field.id === 'invitation_form_volunteer_dni') {
            if (!this.validateDNI(field.value)) {
                isValid = false;
                customErrorMessage = "El formato del DNI/NIE no es correcto.";
            }
        }

        if (isValid) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
            this.removeError(field);
        } else {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            this.showError(field, customErrorMessage);
        }
        return isValid;
    }

    validateDNI(dni) {
        if (!dni) return false;
        const value = dni.toUpperCase();
        const dniRegex = /^[0-9]{8}[A-Z]$/;
        const nieRegex = /^[XYZ][0-9]{7}[A-Z]$/;

        if (!dniRegex.test(value) && !nieRegex.test(value)) {
            return false;
        }

        const dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        let number;
        let letter;

        if (value.startsWith('X')) {
            number = '0' + value.substring(1, 8);
        } else if (value.startsWith('Y')) {
            number = '1' + value.substring(1, 8);
        } else if (value.startsWith('Z')) {
            number = '2' + value.substring(1, 8);
        } else {
            number = value.substring(0, 8);
        }
        letter = value.substring(value.length - 1);

        const calculatedLetter = dniLetters.charAt(parseInt(number) % 23);

        return letter === calculatedLetter;
    }

    showError(field, customMessage = null) {
        let message = customMessage || field.validationMessage;
        if (field.validity.valueMissing) {
            message = "Este campo es obligatorio.";
        } else if (field.validity.patternMismatch && !customMessage) {
            message = "El formato no es correcto.";
        } else if (field.validity.tooShort) {
            message = `El valor es demasiado corto (mínimo ${field.minLength} caracteres).`;
        } else if (field.validity.typeMismatch) {
            message = "El formato del email no es correcto.";
        }


        const errorElement = document.createElement('div');
        errorElement.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
        errorElement.innerText = message;
        // Ensure not to add multiple error messages
        this.removeError(field);
        field.parentNode.insertBefore(errorElement, field.nextSibling);
    }

    removeError(field) {
        const errorElement = field.parentNode.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }

    handleSubmit(event) {
        let isValid = true;
        this.element.querySelectorAll('input, select, textarea').forEach(field => {
            this.checkValidity(field);
            if (!field.checkValidity()) {
                isValid = false;
            }
        });

        if (!isValid) {
            event.preventDefault();
        }
    }
}