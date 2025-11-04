import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "nameInput"];

    connect() {
        // No need to manually find targets, Stimulus does this automatically.
    }

    openModal(event) {
        event.preventDefault();
        const modalId = event.currentTarget.dataset.modalId;
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    closeModal(event) {
        event.preventDefault();
        const modal = event.currentTarget.closest('[data-modal-form-target="modal"]');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    async submit(event) {
        event.preventDefault();
        const form = event.currentTarget;
        const url = form.dataset.url;
        const selectTargetId = form.dataset.selectTarget;
        const formName = form.dataset.formName; // This is 'service_type' or 'service_category'
        const nameInput = form.querySelector('[data-modal-form-target="nameInput"]');
        const name = nameInput.value;

        // Create the correct JSON payload structure, e.g., { "service_type": { "name": "New Value" } }
        const payload = {
            [formName]: {
                name: name
            }
        };

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(data)
        });

        if (response.ok) {
            const data = await response.json();
            const selectElement = document.querySelector(selectTargetId);
            if (selectElement) {
                const option = new Option(data.name, data.id, true, true);
                selectElement.add(option);
                // Trigger a change event for other scripts that might be listening
                selectElement.dispatchEvent(new Event('change'));
            }
            this.closeModal(event);
        } else {
            const data = await response.json();
            alert(data.errors || 'An unknown error occurred.');
        }
    }
}
