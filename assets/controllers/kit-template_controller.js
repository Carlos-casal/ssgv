import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["container", "prototype"];
    index = 0;

    connect() {
        console.log("Kit Template Controller connected");
        // Add first item by default
        this.addItem();
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
