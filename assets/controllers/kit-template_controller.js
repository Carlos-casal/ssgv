import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container", "prototype"];

    connect() {
        this.index = this.containerTarget.querySelectorAll('.kit-item-row').length;
    }

    addItem(event) {
        if (event) {
            event.preventDefault();
        }

        // Use the content of the template tag
        const html = this.prototypeTarget.innerHTML.replace(/__index__/g, this.index);
        this.containerTarget.insertAdjacentHTML('beforeend', html);
        this.index++;

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    removeItem(event) {
        if (event) {
            event.preventDefault();
        }

        const row = event.target.closest('.kit-item-row');
        if (row) {
            row.remove();
        }
    }
}
