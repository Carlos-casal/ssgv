import { Controller } from '@hotwired/stimulus';

/**
 * A simple controller to toggle the visibility of a target element
 * based on whether any checkbox in a group is checked.
 */
export default class extends Controller {
    static targets = ["checkbox", "targetToToggle"];

    connect() {
        this.toggle(); // Run on connect to set initial state
    }

    /**
     * Checks if any of the specified checkboxes are checked and
     * toggles the 'hidden' class on the target element accordingly.
     */
    toggle() {
        // Find at least one checked checkbox within the controller's scope
        const anyChecked = this.checkboxTargets.some(checkbox => checkbox.checked);

        if (this.hasTargetToToggleTarget) {
            this.targetToToggleTarget.classList.toggle('hidden', !anyChecked);
        }
    }
}