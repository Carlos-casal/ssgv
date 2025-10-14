import { Controller } from '@hotwired/stimulus';

/**
 * A simple controller to calculate age from a date input and display it in a modal.
 */
export default class extends Controller {
    static targets = ["modal", "ageText", "dateOfBirthInput"];

    connect() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }

    /**
     * Calculates the age based on the date input and shows the modal.
     */
    calculateAndShowAge() {
        const birthDateString = this.dateOfBirthInputTarget.value;
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

        if (this.hasAgeTextTarget) {
            if (age < 18) {
                this.ageTextTarget.innerHTML = `Tienes ${age} años. <br><br> Al ser menor de edad, necesitarás presentar una autorización de tu tutor legal. <br> La fecha de nacimiento no podrá ser modificada una vez completado el registro.`;
            } else {
                this.ageTextTarget.textContent = `La edad calculada es: ${age} años.`;
            }
        }

        this.openModal();
    }

    /**
     * Shows the modal.
     */
    openModal() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.remove('hidden');
        }
    }

    /**
     * Hides the modal.
     */
    closeModal() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }
}