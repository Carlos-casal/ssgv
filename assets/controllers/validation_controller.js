import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "successMessage", "errorMessage"];
    static values = { type: String }

    connect() {
        this.inputTarget.addEventListener('blur', this.validate.bind(this));
    }

    validate() {
        const value = this.inputTarget.value;
        let isValid = false;

        switch (this.typeValue) {
            case 'dni':
                isValid = this.isValidDni(value);
                break;
            case 'not-empty':
                isValid = value.trim() !== '';
                break;
        }

        if (isValid) {
            this.setSuccess();
        } else {
            this.setError();
        }
    }

    setSuccess() {
        this.inputTarget.classList.remove('border-red-600', 'focus:border-blue-600');
        this.inputTarget.classList.add('border-green-600');

        const label = this.element.querySelector('label');
        if(label) {
            label.classList.remove('text-red-600');
            label.classList.add('text-green-600');
        }

        if (this.hasSuccessMessageTarget) this.successMessageTarget.classList.remove('hidden');
        if (this.hasErrorMessageTarget) this.errorMessageTarget.classList.add('hidden');
    }

    setError() {
        this.inputTarget.classList.remove('border-green-600', 'focus:border-blue-600');
        this.inputTarget.classList.add('border-red-600');

        const label = this.element.querySelector('label');
        if(label){
            label.classList.remove('text-green-600');
            label.classList.add('text-red-600');
        }

        if (this.hasErrorMessageTarget) this.errorMessageTarget.classList.remove('hidden');
        if (this.hasSuccessMessageTarget) this.successMessageTarget.classList.add('hidden');
    }

    isValidDni(dni) {
        const value = dni.toUpperCase();
        this.inputTarget.value = value;
        const dniRegex = /^[XYZ]?\d{5,8}[A-Z]$/;
        if (!dniRegex.test(value)) return false;

        const dniLetters = "TRWAGMYFPDXBNJZSQVHLCKE";
        const number = value.replace(/^[XYZ]/, (letter) => {
            switch (letter) {
                case 'X': return '0';
                case 'Y': return '1';
                case 'Z': return '2';
            }
        }).slice(0, -1);

        return value.slice(-1) === dniLetters[parseInt(number) % 23];
    }
}
