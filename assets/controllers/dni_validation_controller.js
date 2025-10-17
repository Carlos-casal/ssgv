import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input"];

    connect() {
        this.sanitize = this.sanitize.bind(this);
        // The 'validate' method is called via data-action="blur->..." so no listener needed for it.
        this.inputTarget.addEventListener('input', this.sanitize);
    }

    disconnect() {
        this.inputTarget.removeEventListener('input', this.sanitize);
    }

    // This method is now only for sanitizing the input as the user types.
    sanitize(event) {
        let value = event.target.value.toUpperCase().replace(/[^A-Z0-9ÑXYZ]/g, '');
        if (value.length > 9) {
            value = value.slice(0, 9);
        }
        event.target.value = value;
    }

    // This method is called on 'blur' to perform the actual validation.
    validate() {
        const value = this.inputTarget.value;
        this.removeErrorMessage();

        if (value.length === 0) {
            this.inputTarget.classList.add('is-invalid');
            this.inputTarget.classList.remove('is-valid');
            this.addErrorMessage('Este campo es obligatorio.');
            return;
        }

        if (this.isValidNif(value)) {
            this.inputTarget.classList.add('is-valid');
            this.inputTarget.classList.remove('is-invalid');
        } else {
            this.inputTarget.classList.add('is-invalid');
            this.inputTarget.classList.remove('is-valid');
            this.addErrorMessage('El formato del DNI/NIE es incorrecto.');
        }
    }

    addErrorMessage(message) {
        this.removeErrorMessage();
        const errorDiv = document.createElement('div');
        errorDiv.className = 'invalid-feedback dni-error-message';
        errorDiv.textContent = message;
        // The input is inside a wrapper for the floating label. We add the error message after this wrapper.
        this.inputTarget.parentElement.after(errorDiv);
    }

    removeErrorMessage() {
        const wrapper = this.inputTarget.parentElement;
        // The error message is the next sibling of the wrapper div.
        const existingError = wrapper.nextElementSibling;
        if (existingError && existingError.classList.contains('dni-error-message')) {
            existingError.remove();
        }
    }

    isValidNif(nif) {
        const nifRegex = /^[0-9]{8}[A-Z]$/;
        const nieRegex = /^[XYZ][0-9]{7}[A-Z]$/;

        if (!nifRegex.test(nif) && !nieRegex.test(nif)) {
            return false;
        }

        const letter = nif.slice(-1);
        const nifBody = nif.slice(0, -1);

        let num;
        if (nieRegex.test(nif)) {
            let prefix = nifBody.charAt(0);
            let prefixNum = '0';
            if (prefix === 'Y') {
                prefixNum = '1';
            } else if (prefix === 'Z') {
                prefixNum = '2';
            }
            num = parseInt(prefixNum + nifBody.slice(1), 10);
        } else {
            num = parseInt(nifBody, 10);
        }

        const validLetters = 'TRWAGMYFPDXBNJZSQVHLCKE';
        const calculatedLetter = validLetters.charAt(num % 23);

        return letter === calculatedLetter;
    }
}