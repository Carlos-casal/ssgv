import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "form", "successMessage", "emailInput"];

    open() {
        this.modalTarget.classList.remove('hidden');
        this.modalTarget.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    }

    close() {
        this.modalTarget.classList.add('hidden');
        this.modalTarget.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');

        // Reset form after closing
        setTimeout(() => {
            this.formTarget.classList.remove('hidden');
            this.successMessageTarget.classList.add('hidden');
            if (this.hasEmailInputTarget) {
                this.emailInputTarget.value = '';
            }
        }, 300);
    }

    submit(event) {
        event.preventDefault();

        // Simulate sending email
        const email = this.hasEmailInputTarget ? this.emailInputTarget.value : '';
        console.log('Password recovery requested for:', email);

        // Hide form and show success message
        this.formTarget.classList.add('hidden');
        this.successMessageTarget.classList.remove('hidden');
    }
}
