import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["dateInput", "modal", "ageText"];

    connect() {
        // Ensure the modal is hidden on load
        this.modalTarget.classList.add('hidden');
    }

    calculateAndShowAge() {
        const birthDateString = this.dateInputTarget.value;
        if (!birthDateString) {
            return;
        }

        const birthDate = new Date(birthDateString);
        const today = new Date();

        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();

        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        this.ageTextTarget.textContent = `Tienes ${age} años.`;
        this.openModal();
    }

    openModal() {
        this.modalTarget.classList.remove('hidden');
    }

    closeModal() {
        this.modalTarget.classList.add('hidden');
    }
}