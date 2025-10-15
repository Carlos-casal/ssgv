import { Controller } from '@hotwired/stimulus';

/**
 * A simple controller to calculate age from a date input and display it in a modal.
 */
export default class extends Controller {
    static targets = ["modal", "age", "dateOfBirthInput"];

    connect() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }

    /**
     * Calculates the age based on the date input and opens the modal.
     */
    open(event) {
        const birthDateString = event.target.value;
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

        if (this.hasAgeTarget) {
            this.ageTarget.textContent = age;
            const ageInfo = this.modalTarget.querySelector('.age-info');
            if (ageInfo) {
                if (age < 18) {
                    ageInfo.innerHTML = `Al ser menor de edad, necesitarás presentar una autorización de tu tutor legal.`;
                    ageInfo.classList.remove('hidden');
                } else {
                    ageInfo.classList.add('hidden');
                }
            }
        }

        if (this.hasModalTarget) {
            this.modalTarget.classList.remove('hidden');
        }
    }

    /**
     * Hides the modal.
     */
    close() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }
}