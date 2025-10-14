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

        let isValid;

        // Use specific validation for DNI/NIE field
        if (input.id === 'volunteer_dni') {
            isValid = this._validateDniNie(input.value);
        } else if (input.type === 'email') {
            isValid = this._validateEmail(input.value);
        } else if (input.id.includes('Phone')) {
            // If the field is not required and is empty, it's valid.
            if (!input.required && input.value.trim() === '') {
                isValid = true;
            } else {
                isValid = this._validatePhone(input.value);
            }
        } else {
            // Generic validation for all other required fields
            isValid = input.value.trim() !== '';
        }

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
        const isDateInput = input.type === 'date';
        const iconPositionClass = isDateInput ? 'right-10' : 'right-3';
        icon.className = `validation-icon absolute ${iconPositionClass} top-1/2 -translate-y-1/2 flex items-center justify-center`;

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

    /**
     * Converts the input of an event target to uppercase.
     * @param {Event} event The input event.
     */
    toUpperCase(event) {
        event.target.value = event.target.value.toUpperCase();
    }

    /**
     * Validates a Spanish DNI or NIE number.
     * @param {string} value The DNI/NIE to validate.
     * @returns {boolean} True if valid.
     */
    _validateDniNie(value) {
        const dni = value.toUpperCase().trim();
        if (!/^((\d{8})|([XYZ]\d{7}))[A-Z]$/.test(dni)) return false;

        const numberPart = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);
        const controlLetter = 'TRWAGMYFPDXBNJZSQVHLCKE'[parseInt(numberPart, 10) % 23];

        return letter === controlLetter;
    }

    /**
     * Validates an email address format.
     * @param {string} value The email to validate.
     * @returns {boolean} True if valid.
     */
    _validateEmail(value) {
        if (value.trim() === '') return false;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(value);
    }

    /**
     * Validates a phone number format (Spanish 9-digit or international).
     * @param {string} value The phone number to validate.
     * @returns {boolean} True if valid.
     */
    _validatePhone(value) {
        // A 9-digit number or a + followed by 1 to 15 digits.
        const phoneRegex = /^(?:\d{9}|(?:\+)\d{1,15})$/;
        return phoneRegex.test(value.replace(/\s+/g, ''));
    }

    /**
     * Filters the input of a phone field to allow only numbers and a leading '+'.
     * @param {Event} event The input event.
     */
    filterPhoneInput(event) {
        const input = event.target;
        let value = input.value;

        // Allow only digits and a plus sign at the beginning.
        let sanitizedValue = value.replace(/[^\d+]/g, '');

        // Ensure '+' is only at the start.
        if (sanitizedValue.lastIndexOf('+') > 0) {
            sanitizedValue = '+' + sanitizedValue.replace(/\+/g, '');
        }

        input.value = sanitizedValue;
    }
}