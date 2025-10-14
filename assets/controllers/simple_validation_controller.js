import { Controller } from '@hotwired/stimulus';

/**
 * A simple controller to provide visual feedback on form fields.
 * It checks if a required field is empty on blur and applies styles accordingly.
 */
export default class extends Controller {

    /**
     * Validates a single input field when it loses focus (on blur).
     * @param {Event} event The blur event.
     */
    validate(event) {
        const input = event.target;

        // We only care about required fields for this simple validation.
        if (!input.required) {
            this._removeValidationUI(input); // Clean up any previous state if not required
            return;
        }

        const isValid = input.value.trim() !== '';
        this._updateFieldValidationUI(input, isValid);
    }

    /**
     * Updates the UI to show validation status (success or error).
     * @param {HTMLElement} input The input element.
     * @param {boolean} isValid The validation status.
     */
    _updateFieldValidationUI(input, isValid) {
        this._removeValidationUI(input); // Clear previous state first.

        // --- DOM Restructuring for Icon Positioning ---
        // Get the parent of the input field.
        const originalParent = input.parentElement;
        let wrapper = originalParent.querySelector('.input-wrapper');

        // If the wrapper doesn't exist, create it. This is a one-time operation.
        if (!wrapper) {
            // Create the new wrapper div.
            wrapper = document.createElement('div');
            wrapper.className = 'input-wrapper relative';

            // Insert the wrapper into the DOM right after the input field.
            originalParent.insertBefore(wrapper, input.nextSibling);

            // Move the input field inside the new wrapper.
            wrapper.appendChild(input);
        }

        const icon = document.createElement('span');
        icon.className = 'validation-icon absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center';

        if (isValid) {
            input.style.borderColor = '#22c55e'; // Tailwind's green-500
            icon.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        } else {
            input.style.borderColor = '#ef4444'; // Tailwind's red-500
            icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
        }

        // Append the icon to the wrapper, not the original parent.
        wrapper.appendChild(icon);
    }

    /**
     * Removes all validation indicators (styles, icons) from a field.
     * @param {HTMLElement} input The input element.
     */
    _removeValidationUI(input) {
        input.style.borderColor = ''; // Revert to default border color

        // The icon is now inside a wrapper, which is a sibling of the input if it exists.
        const wrapper = input.parentElement.querySelector('.input-wrapper') || input.parentElement;
        const icon = wrapper.querySelector('.validation-icon');

        if (icon) {
            icon.remove();
        }
    }
}