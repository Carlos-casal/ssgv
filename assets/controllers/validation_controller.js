import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "successMessage", "errorMessage"];
    static values = { type: String }

    connect() {
        this.inputTarget.addEventListener('blur', this.validate.bind(this));
        // Store original classes to reset to default state
        this.originalBorderClasses = Array.from(this.inputTarget.classList).filter(c => c.startsWith('border-'));
        this.originalLabelClasses = Array.from(this.element.querySelector('label')?.classList || []).filter(c => c.startsWith('text-'));
    }

    validate() {
        const value = this.inputTarget.value;
        let isValid = false;

        switch (this.typeValue) {
            case 'dni':
                isValid = this.isValidDniNie(value);
                break;
            case 'phone':
                isValid = this.isValidPhone(value);
                break;
            case 'not-empty':
                isValid = value.trim() !== '';
                break;
        }

        if (value.trim() === '') {
            this.resetValidation();
        } else if (isValid) {
            this.setSuccess();
        } else {
            this.setError();
        }
    }

    clearValidationClasses() {
        const classesToRemove = ['border-red-600', 'border-green-600', 'border-gray-300', 'dark:border-gray-600', 'focus:border-blue-600'];
        this.inputTarget.classList.remove(...classesToRemove);

        const label = this.element.querySelector('label');
        if (label) {
            const labelClassesToRemove = ['text-red-600', 'text-green-600', 'text-gray-500'];
            label.classList.remove(...labelClassesToRemove);
        }
    }

    setSuccess() {
        this.clearValidationClasses();
        this.inputTarget.classList.add('border-green-600');

        const label = this.element.querySelector('label');
        if(label) label.classList.add('text-green-600');

        if (this.hasSuccessMessageTarget) this.successMessageTarget.classList.remove('hidden');
        if (this.hasErrorMessageTarget) this.errorMessageTarget.classList.add('hidden');
    }

    setError() {
        this.clearValidationClasses();
        this.inputTarget.classList.add('border-red-600');

        const label = this.element.querySelector('label');
        if(label) label.classList.add('text-red-600');

        if (this.hasErrorMessageTarget) this.errorMessageTarget.classList.remove('hidden');
        if (this.hasSuccessMessageTarget) this.successMessageTarget.classList.add('hidden');
    }

    resetValidation() {
        this.clearValidationClasses();
        this.inputTarget.classList.add(...this.originalBorderClasses);

        const label = this.element.querySelector('label');
        if(label) label.classList.add(...this.originalLabelClasses);

        if (this.hasSuccessMessageTarget) this.successMessageTarget.classList.add('hidden');
        if (this.hasErrorMessageTarget) this.errorMessageTarget.classList.add('hidden');
    }

    isValidPhone(phone) {
        const phoneRegex = /^\+?[0-9]{9,15}$/;
        return phoneRegex.test(phone);
    }

    isValidDniNie(dni) {
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
