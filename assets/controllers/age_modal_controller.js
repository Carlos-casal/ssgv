import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "age", "dateOfBirthInput"];

    connect() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }

    open(event) {
        const birthDateString = event.target.value;
        if (!birthDateString) return;

        const birthDate = new Date(birthDateString);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDifference = today.getMonth() - birthDate.getMonth();
        if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (this.hasAgeTarget) {
            this.ageTarget.textContent = age;
        }

        if (age < 18) {
            if (this.hasModalTarget) {
                this.modalTarget.classList.remove('hidden');
            }
        } else {
            if (this.hasModalTarget) {
                this.modalTarget.classList.add('hidden');
            }
        }
    }

    close() {
        if (this.hasModalTarget) {
            this.modalTarget.classList.add('hidden');
        }
    }
}