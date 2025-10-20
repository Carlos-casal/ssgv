import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "successMessage", "errorMessage"];

    connect() {
        this.inputTarget.addEventListener('input', this.validate.bind(this));
    }

    validate() {
        if (this.inputTarget.value === '') {
            this.reset();
            return;
        }

        if (this.isValidEmail(this.inputTarget.value)) {
            this.setSuccess();
        } else {
            this.setError();
        }
    }

    isValidEmail(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    setSuccess() {
        this.inputTarget.classList.remove('border-red-600');
        this.inputTarget.classList.add('border-green-600');

        const label = this.inputTarget.closest('.form-row').querySelector('label');
        if(label) {
            label.classList.remove('text-red-600');
            label.classList.add('text-green-600');
        }

        this.successMessageTarget.classList.remove('hidden');
        if (this.hasErrorMessageTarget) {
            this.errorMessageTarget.classList.add('hidden');
        }
    }

    setError() {
        this.inputTarget.classList.remove('border-green-600');
        this.inputTarget.classList.add('border-red-600');

        const label = this.inputTarget.closest('.form-row').querySelector('label');
        if(label){
            label.classList.remove('text-green-600');
            label.classList.add('text-red-600');
        }

        if (this.hasErrorMessageTarget) {
            this.errorMessageTarget.classList.remove('hidden');
        }
        this.successMessageTarget.classList.add('hidden');
    }

    reset() {
        this.inputTarget.classList.remove('border-green-600', 'border-red-600');

        const label = this.inputTarget.closest('.form-row').querySelector('label');
        if(label){
            label.classList.remove('text-green-600', 'text-red-600');
        }

        this.successMessageTarget.classList.add('hidden');
        if (this.hasErrorMessageTarget) {
            this.errorMessageTarget.classList.add('hidden');
        }
    }
}
