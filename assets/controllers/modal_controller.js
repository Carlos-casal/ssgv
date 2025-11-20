import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["modal", "invitationForm", "emailPreview", "emailBody", "emailInput"];

    connect() {
        this.boundClose = this.close.bind(this);
    }

    open() {
        this.modalTarget.classList.remove('hidden');
    }

    close(event) {
        // If the click is outside the modal content, close it
        if (event && event.target !== this.modalTarget) {
            return;
        }
        this.resetModal();
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