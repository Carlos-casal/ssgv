import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["input", "errorMessage"];

    connect() {
        this.inputTarget.addEventListener('blur', this.validate.bind(this));
    }

    validate() {
        if (this.inputTarget.value.trim() === '') {
            this.setError();
        } else {
            this.setSuccess();
        }
    }

    setSuccess() {
        this.inputTarget.classList.remove('border-red-600');
        this.inputTarget.classList.add('border-green-600');
        this.errorMessageTarget.classList.add('hidden');
    }

    setError() {
        this.inputTarget.classList.remove('border-green-600');
        this.inputTarget.classList.add('border-red-600');
        this.errorMessageTarget.classList.remove('hidden');
    }
}
