import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input"];

    connect() {
        this.inputTarget.addEventListener('input', this.validate.bind(this));
    }

    validate(event) {
        let value = event.target.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (value.length > 9) {
            value = value.slice(0, 9);
        }
        event.target.value = value;

        if (value.length === 9) {
            if (this.isValidNif(value)) {
                this.inputTarget.classList.add('is-valid');
                this.inputTarget.classList.remove('is-invalid');
            } else {
                this.inputTarget.classList.add('is-invalid');
                this.inputTarget.classList.remove('is-valid');
            }
        } else {
            this.inputTarget.classList.remove('is-valid');
            this.inputTarget.classList.remove('is-invalid');
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