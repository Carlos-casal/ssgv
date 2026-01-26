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

        const email = this.hasEmailInputTarget ? this.emailInputTarget.value : '';

        fetch('/forgot-password', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            // Agnostic success message
            this.formTarget.classList.add('hidden');
            this.successMessageTarget.classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error:', error);
            // Even on error, we might want to show the agnostic message for security
            // but let's at least log it.
            this.formTarget.classList.add('hidden');
            this.successMessageTarget.classList.remove('hidden');
        });
    }
}
