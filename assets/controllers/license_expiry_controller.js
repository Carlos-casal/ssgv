import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["licenseContainer", "checkboxes", "prototypes"];

    connect() {
        const licenseTypes = ['A1', 'A', 'B', 'C1', 'C', 'D1', 'D', 'EC'];
        const container = this.checkboxesTarget;

        licenseTypes.forEach(type => {
            const div = document.createElement('div');
            div.className = 'flex items-center space-x-2';

            const checkbox = document.createElement('input');
            checkbox.type = 'checkbox';
            checkbox.id = `license_${type}`;
            checkbox.dataset.type = type;
            checkbox.dataset.action = 'change->license-expiry#toggleDateInput';

            const label = document.createElement('label');
            label.htmlFor = `license_${type}`;
            label.textContent = type;

            div.appendChild(checkbox);
            div.appendChild(label);
            container.appendChild(div);
        });

        this.element.addEventListener('submit', this.prepareSubmission.bind(this));
    }

    toggleDateInput(event) {
        const checkbox = event.target;
        const type = checkbox.dataset.type;
        const parentDiv = checkbox.parentElement;

        let dateInputContainer = parentDiv.querySelector('.date-input-container');

        if (checkbox.checked) {
            if (!dateInputContainer) {
                dateInputContainer = document.createElement('div');
                dateInputContainer.className = 'date-input-container ml-4';

                const dateInput = document.createElement('input');
                dateInput.type = 'date';
                dateInput.dataset.type = type;
                dateInput.className = 'form-input p-1 border border-gray-300 rounded-lg';

                dateInputContainer.appendChild(dateInput);
                parentDiv.appendChild(dateInputContainer);
            }
        } else {
            if (dateInputContainer) {
                dateInputContainer.remove();
            }
        }
    }

    prepareSubmission(event) {
        const selectedLicenses = [];
        this.checkboxesTarget.querySelectorAll('input[type="checkbox"]:checked').forEach(checkbox => {
            const type = checkbox.dataset.type;
            const dateInput = checkbox.parentElement.querySelector('input[type="date"]');
            const expiryDate = dateInput ? dateInput.value : '';

            selectedLicenses.push({
                type: type,
                expiryDate: expiryDate,
            });
        });

        // Remove old fields to avoid submission conflicts
        const oldFields = this.element.querySelectorAll('[name^="volunteer[drivingLicenses]"]');
        oldFields.forEach(field => field.remove());

        // Add new hidden fields with the correct data structure
        selectedLicenses.forEach((license, index) => {
            const typeInput = document.createElement('input');
            typeInput.type = 'hidden';
            typeInput.name = `volunteer[drivingLicenses][${index}][type]`;
            typeInput.value = license.type;
            this.element.appendChild(typeInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = `volunteer[drivingLicenses][${index}][expiryDate]`;
            dateInput.value = license.expiryDate;
            this.element.appendChild(dateInput);
        });
    }
}