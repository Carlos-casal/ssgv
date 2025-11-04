import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "nameInput"];

    connect() {
        // Automatically find all modals within the controller's scope
        this.modalTargets = this.element.querySelectorAll('[data-modal-form-target="modal"]');
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
        const name = form.querySelector('[data-modal-form-target="nameInput"]').value;

        // Use the form's name attribute to build the payload key
        const formName = form.closest('div.bg-white').querySelector('form').name;
        const payloadKey = `${formName}[name]`;

        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: new URLSearchParams({ [payloadKey]: name })
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
