import { Controller } from '@hotwired/stimulus';

/**
 * Toggles the visibility of a corresponding date input when a checkbox is checked.
 */
export default class extends Controller {
    static targets = ["dateContainer"];

    toggle() {
        if (this.hasDateContainerTarget) {
            this.dateContainerTarget.classList.toggle('hidden');
        }
    }
}