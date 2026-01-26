import { Controller } from '@hotwired/stimulus';

// Controller to toggle password visibility
export default class extends Controller {
    static targets = ['input', 'eyeIcon', 'eyeOffIcon'];

    show() {
        this.inputTarget.type = 'text';
        this.eyeIconTarget.classList.add('hidden');
        this.eyeOffIconTarget.classList.remove('hidden');
    }

    hide() {
        this.inputTarget.type = 'password';
        this.eyeIconTarget.classList.remove('hidden');
        this.eyeOffIconTarget.classList.add('hidden');
    }
}
