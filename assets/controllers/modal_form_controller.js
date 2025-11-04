import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "nameInput"];

    connect() {
        this.typeForm = document.getElementById('modal-new-type-form');
        this.categoryForm = document.getElementById('modal-new-category-form');

        if (this.typeForm) {
            this.typeForm.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
        if (this.categoryForm) {
            this.categoryForm.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    disconnect() {
        if (this.typeForm) {
            this.typeForm.removeEventListener('submit', this.handleFormSubmit.bind(this));
        }
        if (this.categoryForm) {
            this.categoryForm.removeEventListener('submit', this.handleFormSubmit.bind(this));
        }
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

    async handleFormSubmit(event) {
        event.preventDefault();
        const form = event.currentTarget;
        const modal = form.closest('[data-modal-form-target="modal"]');
        const url = form.dataset.url;
        const selectTargetId = form.dataset.selectTarget;
        const formName = form.dataset.formName;
        const nameInput = form.querySelector('[data-modal-form-target="nameInput"]');
        const name = nameInput.value;

        const payload = {
            [formName]: {
                name: name
            }
        };

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify(payload)
            });

            if (response.ok) {
                const data = await response.json();
                const selectElement = document.querySelector(selectTargetId);
                if (selectElement) {
                    const option = new Option(data.name, data.id, true, true);
                    selectElement.add(option);
                    selectElement.dispatchEvent(new Event('change'));
                }
                if (modal) {
                    modal.classList.add('hidden');
                }
            } else {
                const data = await response.json();
                alert(data.errors || 'An unknown error occurred.');
            }
        } catch (error) {
            console.error('Submission failed:', error);
            alert('An error occurred while submitting the form.');
        }
    }
}
