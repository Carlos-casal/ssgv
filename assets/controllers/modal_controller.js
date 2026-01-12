import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "content", "invitationForm", "emailPreview", "emailBody", "emailInput"];

    connect() {
        // No need to bind 'close' anymore as we're not using it with window events
    }

    open(event) {
        // Stop the event from bubbling up and immediately closing the modal.
        event.stopPropagation();
        this.modalTarget.classList.remove('hidden');
    }

    close(event) {
        // Close the modal if the click is directly on the overlay background.
        if (event.target === this.modalTarget) {
            this.resetModal();
        }
    }

    closeButton() {
        this.resetModal();
    }

    resetModal() {
        this.modalTarget.classList.add('hidden');
        this.invitationFormTarget.classList.remove('hidden');
        this.emailPreviewTarget.classList.add('hidden');
        if (this.hasEmailInputTarget) {
            this.emailInputTarget.value = '';
        }
    }

    sendInvitation() {
        const email = this.emailInputTarget.value.trim();

        if (!email) {
            alert('Por favor, introduce una dirección de correo electrónico.');
            return;
        }

        fetch('/send-invitation', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_dev) {
                this.invitationFormTarget.classList.add('hidden');
                this.emailPreviewTarget.classList.remove('hidden');
                this.emailBodyTarget.innerHTML = data.email_body;
            } else if (data.message) {
                alert('Invitación enviada correctamente a ' + email);
                this.resetModal();
            } else {
                alert('Error al enviar la invitación.');
                this.resetModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar la invitación.');
            this.resetModal();
        });
    }
}