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

        const iconContainer = input.parentElement;
        const icon = document.createElement('span');
        icon.className = 'validation-icon absolute right-3 top-1/2 -translate-y-1/2 flex items-center justify-center';

        if (isValid) {
            input.style.borderColor = '#22c55e'; // Tailwind's green-500
            icon.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        } else {
            input.style.borderColor = '#ef4444'; // Tailwind's red-500
            icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
        }

        // Ensure the parent element can contain the absolutely positioned icon
        if (getComputedStyle(iconContainer).position === 'static') {
            iconContainer.style.position = 'relative';
        }

        iconContainer.appendChild(icon);
    }

    /**
     * Removes all validation indicators (styles, icons) from a field.
     * @param {HTMLElement} input The input element.
     */
    _removeValidationUI(input) {
        input.style.borderColor = ''; // Revert to default border color
        const icon = input.parentElement.querySelector('.validation-icon');
        if (icon) {
            icon.remove();
        }
    }
}