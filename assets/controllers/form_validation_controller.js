import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        this.element.addEventListener('submit', this.handleSubmit.bind(this));
        // Add blur listeners to all relevant inputs
        this.element.querySelectorAll('input[data-action], select[data-action], textarea[data-action]').forEach(input => {
            const actions = input.dataset.action.split(' ');
            actions.forEach(action => {
                const [event, handler] = action.split('->');
                const methodName = handler.split('#')[1];
                if (this[methodName]) {
                    input.addEventListener(event, this[methodName].bind(this));
                }
            });
        });
        this.toggleDrivingLicenseExpiry();
        this.togglePreviousInstitutions();
        this.setDateOfBirthMaxDate();
    }

    setDateOfBirthMaxDate() {
        const dateOfBirthInput = this.element.querySelector('#volunteer_dateOfBirth');
        if (!dateOfBirthInput) return;

        const today = new Date();
        const maxYear = today.getFullYear() - 16;
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        const maxDate = `${maxYear}-${month}-${day}`;
        dateOfBirthInput.setAttribute('max', maxDate);
    }

    // --- Conditional Field Logic ---
    toggleDrivingLicenseExpiry() {
        const drivingLicensesCheckboxes = this.element.querySelectorAll('input[name="volunteer[drivingLicenses][]"]');
        const expiryWrapper = document.getElementById('driving-license-expiry-wrapper');
        if (!expiryWrapper) return;

        const anyChecked = Array.from(drivingLicensesCheckboxes).some(cb => cb.checked);
        expiryWrapper.classList.toggle('hidden', !anyChecked);
        const expiryInput = expiryWrapper.querySelector('input');
        if(expiryInput) {
            expiryInput.required = anyChecked;
            if (!anyChecked) this.removeValidation(expiryInput);
        }
    }

    togglePreviousInstitutions() {
        const hasVolunteeredYes = this.element.querySelector('input[name="volunteer[hasVolunteeredBefore]"][value="1"]');
        const institutionsWrapper = document.getElementById('previous-institutions-wrapper');
        if (!institutionsWrapper) return;

        const isYesChecked = hasVolunteeredYes && hasVolunteeredYes.checked;
        institutionsWrapper.classList.toggle('hidden', !isYesChecked);
        const textarea = institutionsWrapper.querySelector('textarea');
        if (textarea) {
            textarea.required = isYesChecked;
            if (!isYesChecked) this.removeValidation(textarea);
        }
    }

    // --- Form Actions ---
    addAnother(event) {
        event.preventDefault();
        this.element.reset();
        this.element.querySelectorAll('.validation-icon, .form-error-message').forEach(el => el.remove());
        this.element.querySelectorAll('.is-valid, .is-invalid').forEach(el => {
            el.classList.remove('is-valid', 'is-invalid');
            el.style.paddingLeft = '';
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
            const firstInvalid = this.element.querySelector('.is-invalid');
            if(firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }

    // --- Real-time Validation (on blur) ---
    validateOnInput(event) {
        const input = event.target;
        if (input.id === 'volunteer_dni') {
            const isValid = this.validateDniNie(input.value);
            if (isValid) {
                this.updateFieldValidation(input, true, '');
            } else {
                // On input, only remove validation, don't show error
                this.removeValidation(input);
            }
        }
    }

    validate(event) {
        const input = event.target;
        let isValid = true;
        let message = '';

        if (!input.required && input.value.trim() === '') {
            this.removeValidation(input);
            return true;
        }

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
                    const phoneValue = input.value.trim().replace('+34', '');
                    const phoneRegex = /^[679]\d{8}$/;
                    if (!phoneRegex.test(phoneValue)) {
                        isValid = false;
                        message = 'El formato del teléfono no es válido (9 dígitos).';
                    }
                    break;
                case 'volunteer_dateOfBirth':
                    const birthDate = new Date(input.value);
                    if (isNaN(birthDate.getTime())) {
                        isValid = false;
                        message = 'La fecha no es válida.';
                        break;
                    }
                    const today = new Date();
                    const sixteenYearsAgo = new Date(today.getFullYear() - 16, today.getMonth(), today.getDate());
                    if (birthDate > sixteenYearsAgo) {
                        isValid = false;
                        message = 'El voluntario debe tener al menos 16 años cumplidos.';
                    }
                    break;
                 case 'volunteer_email':
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(input.value)) {
                        isValid = false;
                        message = 'El formato del correo no es válido.';
                    }
                    break;
            }
        }

        this.updateFieldValidation(input, isValid, message);
        return isValid;
    }

    validateDniNie(value) {
        const dni = value.toUpperCase().trim();
        if (!/^((\d{8})|([XYZ]\d{7}))[A-Z]$/.test(dni)) return false;

        const numberPart = dni.substr(0, dni.length - 1).replace('X', 0).replace('Y', 1).replace('Z', 2);
        const letter = dni.substr(dni.length - 1, 1);

        return 'TRWAGMYFPDXBNJZSQVHLCKE'[parseInt(numberPart) % 23] === letter;
    }

    // --- UI Update Helpers ---
    updateFieldValidation(input, isValid, message) {
        this.removeValidation(input);
        const fieldContainer = input.parentElement; // The div containing the input
        if (!fieldContainer) return;

        // Ensure the container is relative for icon positioning
        fieldContainer.classList.add('relative');
        const icon = document.createElement('span');
        icon.className = 'validation-icon absolute right-2 top-1/2 -translate-y-1/2';

        if (isValid) {
            input.classList.add('is-valid');
            icon.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            fieldContainer.appendChild(icon);
        } else {
            input.classList.add('is-invalid');
            // Add error icon only if there's content, not for simple "required"
            if (input.value.trim() !== '') {
                icon.innerHTML = `<svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
                fieldContainer.appendChild(icon);
            }

            if (message) {
                 const errorEl = document.createElement('p');
                 errorEl.className = 'form-error-message text-xs text-red-600 mt-1';
                 errorEl.textContent = message;
                 // Append error to the top-level div of the field for correct positioning
                 fieldContainer.closest('.flex').parentElement.appendChild(errorEl);
            }
        }
    }

    removeValidation(input) {
        input.classList.remove('is-valid', 'is-invalid');
        const fieldContainer = input.parentElement;
        if (!fieldContainer) return;

        fieldContainer.querySelector('.validation-icon')?.remove();
        // Find the error message in the correct parent and remove it
        fieldContainer.closest('.flex').parentElement.querySelector('.form-error-message')?.remove();
    }
}