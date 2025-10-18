import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input"];

    connect() {
        this.inputTarget.addEventListener('input', this.validate.bind(this));
    }

    validate() {
        const field = this.inputTarget;
        const value = field.value.toUpperCase();

        this.removeError();

        if (this.isValidDNI(value)) {
            field.classList.add('is-valid');
            field.classList.remove('is-invalid');
        } else {
            field.classList.add('is-invalid');
            field.classList.remove('is-valid');
            if (value.length > 0) {
                this.showError("El formato del DNI/NIE no es correcto.");
            }
        }
    }

    isValidDNI(dni) {
        if (!dni) return false;
        const dniRegex = /^[0-9]{8}[A-Z]$/;
        const nieRegex = /^[XYZ][0-9]{7}[A-Z]$/;

        if (!dniRegex.test(dni) && !nieRegex.test(dni)) {
            return false;
        }

        const dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        let number;
        let letter;

        if (dni.startsWith('X')) {
            number = '0' + dni.substring(1, 8);
        } else if (dni.startsWith('Y')) {
            number = '1' + dni.substring(1, 8);
        } else if (dni.startsWith('Z')) {
            number = '2' + dni.substring(1, 8);
        } else {
            number = dni.substring(0, 8);
        }
        letter = dni.substring(dni.length - 1);

        const calculatedLetter = dniLetters.charAt(parseInt(number) % 23);

        return letter === calculatedLetter;
    }

    showError(message) {
        const errorElement = document.createElement('div');
        errorElement.classList.add('error-message', 'text-red-500', 'text-sm', 'mt-1');
        errorElement.innerText = message;
        this.element.appendChild(errorElement);
    }

    removeError() {
        const errorElement = this.element.querySelector('.error-message');
        if (errorElement) {
            errorElement.remove();
        }
    }
}