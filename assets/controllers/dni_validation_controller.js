import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input"];

    connect() {
        this.inputTarget.addEventListener('input', this.validate.bind(this));
    }

    validate() {
        let value = this.inputTarget.value.toUpperCase();
        this.inputTarget.value = value;

        if (this.isValidDni(value)) {
            this.inputTarget.classList.remove('border-red-600');
            this.inputTarget.classList.add('border-green-600');
        } else {
            this.inputTarget.classList.remove('border-green-600');
            this.inputTarget.classList.add('border-red-600');
        }
    }

    isValidDni(dni) {
        const dniRegex = /^[XYZ]?\d{5,8}[A-Z]$/;
        if (!dniRegex.test(dni)) {
            return false;
        }

        const dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        const number = dni.replace(/^[XYZ]/, (letter) => {
            switch (letter) {
                case 'X': return '0';
                case 'Y': return '1';
                case 'Z': return '2';
            }
        }).slice(0, -1);

        return dni.slice(-1) === dniLetters[parseInt(number) % 23];
    }
}
