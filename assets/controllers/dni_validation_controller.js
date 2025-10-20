import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "successMessage", "errorMessage"];

    connect() {
        this.inputTarget.addEventListener('input', this.validate.bind(this));
    }

    validate() {
        let value = this.inputTarget.value.toUpperCase();
        this.inputTarget.value = value;

        if (value === '') {
            this.reset();
            return;
        }

        if (this.isValidDni(value)) {
            this.setSuccess();
        } else {
            this.setError();
        }
    }

    setSuccess() {
        this.inputTarget.classList.remove('border-red-600');
        this.inputTarget.classList.add('border-green-600');
        this.successMessageTarget.classList.remove('hidden');
        this.errorMessageTarget.classList.add('hidden');
    }

    setError() {
        this.inputTarget.classList.remove('border-green-600');
        this.inputTarget.classList.add('border-red-600');
        this.errorMessageTarget.classList.remove('hidden');
        this.successMessageTarget.classList.add('hidden');
    }

    reset() {
        this.inputTarget.classList.remove('border-green-600', 'border-red-600');
        this.successMessageTarget.classList.add('hidden');
        this.errorMessageTarget.classList.add('hidden');
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
