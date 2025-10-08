import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('submit', this.handleSubmit.bind(this));
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
    }

    // --- Conditional Field Logic ---

    toggleDrivingLicenseExpiry() {
        const drivingLicensesCheckboxes = this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]');
        const expiryWrapper = document.getElementById('driving-license-expiry-wrapper');
        if (!expiryWrapper) return;

        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);
        expiryWrapper.classList.toggle('hidden', !anyChecked);
        expiryWrapper.querySelector('input').required = anyChecked;
    }

    togglePreviousInstitutions() {
        const hasVolunteeredYes = this.element.querySelector('input[name="volunteer[hasVolunteeredBefore]"][value="1"]');
        const institutionsWrapper = document.getElementById('previous-institutions-wrapper');
        if (!institutionsWrapper) return;

        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;
        institutionsWrapper.classList.toggle('hidden', !isYesChecked);
        institutionsWrapper.querySelector('textarea').required = isYesChecked;
    }

    // --- Form Actions ---

    addAnother(event) {
        event.preventDefault();
        this.element.reset();
        this.element.querySelectorAll('.validation-icon, .form-error-message').forEach(el => el.remove());
        this.element.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
        });
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
        window.scrollTo(0, 0);
    }

    handleSubmit(event) {
        let isFormValid = true;
        this.element.querySelectorAll('input[required], select[required], textarea[required]').forEach(input => {
            if (!this.validate({ target: input })) {
                isFormValid = false;
            }
        });

        if (!isFormValid) {
            event.preventDefault();
        }
    }

    // --- Real-time Validation ---

    validate(event) {
        const input = event.target;
        let isValid = true;
        let message = '';

        if (input.required && input.value.trim() === '') {
            isValid = false;
            message = 'Este campo es obligatorio.';
        } else {
            switch (input.id) {
                case 'volunteer_dni':
                    isValid = this.validateDniNie(input.value);
                    if (!isValid) message = 'El DNI/NIE no es válido.';
                    break;
                case 'volunteer_phone':
                    const phoneValue = input.value.startsWith('+34') ? input.value.substring(3) : input.value;
                    const phoneRegex = /^[6789]\d{8}$/;
                    if (!phoneRegex.test(phoneValue)) {
                        isValid = false;
                        message = 'El formato del teléfono no es válido.';
                    }
                    break;
            }
        }

        this.updateFieldValidation(input, isValid, message);
        return isValid;
    }

    validateDniNie(value) {
        const dni = value.toUpperCase();
        if (!/^[XYZ\d]\d{7}[A-Z]$/.test(dni)) return false;

        let number = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);

        return 'TRWAGMYFPDXBNJZSQVHLCKE'[number % 23] === letter;
    }

    // --- UI Update Helpers ---

    updateFieldValidation(input, isValid, message) {
        this.removeValidation(input);

        const iconWrapper = document.createElement('span');
        iconWrapper.className = 'validation-icon absolute right-3 top-1/2 transform -translate-y-1/2';

        if (isValid) {
            input.classList.add('is-valid');
            iconWrapper.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
        } else {
            input.classList.add('is-invalid');
            iconWrapper.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;

            const errorEl = document.createElement('p');
            errorEl.className = 'form-error-message text-xs text-red-600 mt-1';
            errorEl.textContent = message;
            input.parentElement.appendChild(errorEl);
        }

        input.parentElement.classList.add('relative');
        input.parentElement.appendChild(iconWrapper);
    }

    removeValidation(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const parent = input.parentElement;
        parent.querySelector('.validation-icon')?.remove();
        parent.querySelector('.form-error-message')?.remove();
    }
}