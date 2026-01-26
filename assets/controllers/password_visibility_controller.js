import { Controller } from '@hotwired/stimulus';

/* stimulusFetch: 'lazy' */
// Controller to toggle password visibility
export default class extends Controller {
    static targets = ['input', 'eyeIcon', 'eyeOffIcon'];

    toggle() {
        const isPassword = this.inputTarget.type === 'password';

        this.inputTarget.type = isPassword ? 'text' : 'password';

        if (isPassword) {
            this.eyeIconTarget.classList.add('hidden');
            this.eyeOffIconTarget.classList.remove('hidden');
        } else {
            this.eyeIconTarget.classList.remove('hidden');
            this.eyeOffIconTarget.classList.add('hidden');
        }
    }
}
