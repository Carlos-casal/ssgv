import { Controller } from '@hotwired/stimulus';
import { createIcons, icons } from 'lucide';

/* stimulusFetch: 'lazy' */
export default class extends Controller {
    static targets = ['input', 'icon'];

    toggle() {
        if (this.inputTarget.type === 'password') {
            this.inputTarget.type = 'text';
            this.iconTarget.setAttribute('data-lucide', 'eye-off');
        } else {
            this.inputTarget.type = 'password';
            this.iconTarget.setAttribute('data-lucide', 'eye');
        }

        // Refresh Lucide icons
        createIcons({ icons });
    }
}
