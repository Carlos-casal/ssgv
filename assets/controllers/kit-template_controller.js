import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container", "prototype"];
    index = 0;

    connect() {
        console.log("Kit Template Controller connected");
        // Add first item by default if container is empty
        if (this.containerTarget.querySelectorAll('.kit-item-row').length === 0) {
            this.addItem();
        }

        // Use event delegation for remove buttons that might exist in edit mode
        this.element.addEventListener('click', (e) => {
            const removeBtn = e.target.closest('[data-action="click->kit-template#removeItem"]');
            if (removeBtn) {
                this.removeItem(e);
            }
        });

        // Initialize index based on existing rows
        this.index = this.containerTarget.querySelectorAll('.kit-item-row').length;
    }

    addItem() {
        console.log("Adding item...");
        const prototype = this.prototypeTarget.innerHTML.replace(/__index__/g, this.index);
        this.containerTarget.insertAdjacentHTML('beforeend', prototype);
        this.index++;

        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    removeItem(event) {
        event.currentTarget.closest('tr').remove();
    }
}
